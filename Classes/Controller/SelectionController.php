<?php

declare(strict_types=1);

namespace Quizpalme\Tinyaccordion\Controller;

use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Connection;
use Psr\Http\Message\ResponseInterface;


/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Kurt Gusbeth <info@quizpalme.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * TinyAccordion: Ausgabe der Dokumenten-Auswahl als Accordion
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class SelectionController extends ActionController
{

    /**
     * @var ContentObjectRenderer Object
     */
    protected $cObj;

    /**
     * Parse a content element
     */
    private function myRender(string $table, int $uid): string
    {
        $conf = [
            'tables' => $table,
            'source' => $uid,
            'dontCheckPid' => 1
        ];
        $frontendController = $this->request->getAttribute('frontend.controller');
        return $frontendController->cObj->cObjGetSingle('RECORDS', $conf);
    }

    /**
     * get the PID(s)
     *
     * @return string
     */
    private function getPidAndInit(): string
    {
        $this->cObj = $this->request->getAttribute('currentContentObject');
        $pid = 0;

        if (!($this->cObj->data['pages'] == '')) {
            $pid = addslashes((string) $this->cObj->data['pages']);
        } else {
            // Unter-Ordner mit Dokumenten finden
            $pageArguments = $this->request->getAttribute('routing');
            $pageId = $pageArguments->getPageId();
            // Query aufbauen
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content')->createQueryBuilder();
            $result = $queryBuilder ->select('uid') -> from ('pages')
                ->where( $queryBuilder->expr()->eq('pid', intval($pageId)) )
                ->andWhere(  $queryBuilder->expr()->eq('doktype', 254) )->setMaxResults(1)
                -> orderBy('sorting', 'ASC')
                -> executeQuery();
            foreach($result->fetchAllAssociative() as $row) {
               $pid = $row['uid'];
            }
        }
        if (!$pid) {
            $pageArguments = $this->request->getAttribute('routing');
            $pid = $pageArguments->getPageId();
        }

        return (string) $pid;
    }

    /**
     * action content
     *
     * @return ResponseInterface  */
    public function contentAction(): ResponseInterface
    {
        $pids = $this->getPidAndInit();
        $languageAspect = GeneralUtility::makeInstance(Context::class)->getAspect('language');
        $sys_language_uid = intval($languageAspect->getId());
        $pidsArray = explode(',', $pids);
        $dataArray = [];
        $noData = true;
        $order = ($this->settings['flexform']['sortorder']=='desc') ? 'DESC' : 'ASC';
        $mode = ($this->settings['flexform']['sortMode']=='1') ? true : false;
        $renderEverything = ($this->settings['flexform']['renderEverything']=='1') ? true : false;
        $colPos = intval($this->settings['flexform']['colPos']);
        if ($mode) {
            $pidsForeach = $pidsArray;
        } else {
            $pidsForeach[] = $pids;
        }

        // Dokumente holen
        foreach ($pidsForeach as $pid) {
            
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content')->createQueryBuilder();
            $queryBuilder ->select(...[
                'uid',
                'pid',
                'header',
                'tstamp'
            ]) -> from ('tt_content');
            
            if ($mode) {
                $queryBuilder->where(
                    $queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter(intval($pid), Connection::PARAM_INT))
                );
            } else {
                $queryBuilder->where(
                    $queryBuilder->expr()->in('pid', $queryBuilder->createNamedParameter($pidsArray, Connection::PARAM_INT_ARRAY))
                );
            }
            
            if ($colPos == -1) {
                //$whereColPos = '';
            } else {
                $queryBuilder->andWhere(
                    $queryBuilder->expr()->eq('colPos', $queryBuilder->createNamedParameter($colPos, Connection::PARAM_INT))
                );
            }
            if ($renderEverything) {
                //$whereCType = '';
            } else {
                $queryBuilder->andWhere(
                    $queryBuilder->expr()->or(
                        $queryBuilder->expr()->eq('CType', $queryBuilder->createNamedParameter('text')),
                        $queryBuilder->expr()->eq('CType', $queryBuilder->createNamedParameter('table')),
                        $queryBuilder->expr()->eq('CType', $queryBuilder->createNamedParameter('textpic')),
                        $queryBuilder->expr()->eq('CType', $queryBuilder->createNamedParameter('textmedia'))
                    )
                );
            }
            
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq('sys_language_uid', $queryBuilder->createNamedParameter($sys_language_uid, Connection::PARAM_INT))
            );

            $queryBuilder->orderBy('colPos')->addOrderBy('sorting', $order);
            //debug($queryBuilder->getSQL());
            $statement = $queryBuilder->executeQuery();
            
            foreach ($statement->fetchAllAssociative() as $row) {
                    $dataArray[$row['uid']] = [];
                    $dataArray[$row['uid']]['pid'] = $row['pid'];
                    $dataArray[$row['uid']]['header'] = $row['header'];
                    $dataArray[$row['uid']]['datetime'] = $row['tstamp'];
                    $noData = false;
            }
        }

        // Dokumente rendern
        if (count($dataArray)>0) {
            foreach ($dataArray as $uid => $value) {
                $dataArray[$uid]['bodytext'] = $this->myRender('tt_content', $uid);
            }
        }

        $this->view->assign('elements', $dataArray);
        $this->view->assign('nodata', $noData);
        $this->view->assign('uid', $this->cObj->data['uid']);
        $this->view->assign('pids', $pidsArray);
        return $this->htmlResponse();
    }

    /**
     * action tt_content + UI
     *
     * @return ResponseInterface  */
    public function content_ui_accordionAction(): ResponseInterface
    {
        $this->contentAction();
        return $this->htmlResponse();
    }

    /**
     * action pages
     *
     * @return ResponseInterface  */
    public function pagesAction(): ResponseInterface
    {
        $pids = $this->getPidAndInit();
        $languageAspect = GeneralUtility::makeInstance(Context::class)->getAspect('language');
        $sys_language_uid = intval($languageAspect->getId());
        $pidsArray = explode(',', $pids);
        $dataArray = [];
        $noData = true;
        $order = ($this->settings['flexform']['sortorder']=='desc') ? 'DESC' : 'ASC';
        $mode = ($this->settings['flexform']['sortMode']=='1') ? true : false;
        $renderEverything = ($this->settings['flexform']['renderEverything']=='1') ? true : false;
        $colPos = intval($this->settings['flexform']['colPos']);
        $select = ($this->settings['flexform']['select']=='pid') ? 'pid' : 'uid';
        if ($mode) {
            $pidsForeach = $pidsArray;
        } else {
            $pidsForeach = [$pids];
        }
        $uids = '';
        $foundPids = [];

        // Ordner holen
        foreach ($pidsForeach as $pid) {
            
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('pages')->createQueryBuilder();
            $queryBuilder ->select(...[
                'uid',
                'pid',
                'l10n_parent',
                'title',
                'subtitle',
                'abstract',
                'description'
            ]) -> from ('pages');
            
            if ($mode) {
                $queryBuilder->where(
                    $queryBuilder->expr()->eq($select, $queryBuilder->createNamedParameter(intval($pid), Connection::PARAM_INT)),
                    $queryBuilder->expr()->eq('doktype', 1)
                );
            } else {
                $queryBuilder->where(
                    $queryBuilder->expr()->in($select, $queryBuilder->createNamedParameter($pidsArray, Connection::PARAM_INT_ARRAY)),
                    $queryBuilder->expr()->eq('doktype', 1)
                );
            }
            
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq('sys_language_uid', $queryBuilder->createNamedParameter($sys_language_uid, Connection::PARAM_INT))
            );
            
            $queryBuilder->orderBy('sorting', $order);
            //debug($queryBuilder->getSQL());
            $statement = $queryBuilder->executeQuery();
            
            foreach ($statement->fetchAllAssociative() as $row) {
                $uid = ($sys_language_uid > 0) ? $row['l10n_parent'] : $row['uid'];
                $uids .= ($uids) ? ',' . $uid : $uid;
                $foundPids[] = $uid;
                $dataArray[$uid] = [];
                $dataArray[$uid]['pid'] = $row['pid'];
                $dataArray[$uid]['title'] = $row['title'];
                $dataArray[$uid]['subtitle'] = $row['subtitle'];
                $dataArray[$uid]['abstract'] = $row['abstract'];
                $dataArray[$uid]['description'] = $row['description'];
                $dataArray[$uid]['elements'] = [];
                $noData = false;
            }
        }

        // Dokumente aus den Ordnern holen
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content')->createQueryBuilder();
        $queryBuilder ->select(...[
            'uid',
            'pid',
            'header',
            'tstamp'
        ]) -> from ('tt_content');
        
        $uidsArray = explode(',', $uids);
        $queryBuilder->where(
            $queryBuilder->expr()->in('pid', $queryBuilder->createNamedParameter($uidsArray, Connection::PARAM_INT_ARRAY))
        );
        
        if ($colPos == -1) {
            //$whereColPos = '';
        } else {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq('colPos', $queryBuilder->createNamedParameter($colPos, Connection::PARAM_INT))
            );
        }
        if ($renderEverything) {
            //$whereCType = '';
        } else {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->or(
                    $queryBuilder->expr()->eq('CType', $queryBuilder->createNamedParameter('text')),
                    $queryBuilder->expr()->eq('CType', $queryBuilder->createNamedParameter('table')),
                    $queryBuilder->expr()->eq('CType', $queryBuilder->createNamedParameter('textpic')),
                    $queryBuilder->expr()->eq('CType', $queryBuilder->createNamedParameter('textmedia'))
                )
            );
        }
        
        $queryBuilder->andWhere(
            $queryBuilder->expr()->eq('sys_language_uid', $queryBuilder->createNamedParameter($sys_language_uid, Connection::PARAM_INT))
        );
        
        $queryBuilder->orderBy('sorting', $order);
        //debug($queryBuilder->getSQL());
        $statement = $queryBuilder->executeQuery();
        
        foreach ($statement->fetchAllAssociative() as $row) {
            $uid = $row['uid'];
            $pid = $row['pid'];
            $dataArray[$pid]['elements'][$uid] = [];
            $dataArray[$pid]['elements'][$uid]['pid'] = $pid;
            $dataArray[$pid]['elements'][$uid]['header'] = $row['header'];
            $dataArray[$pid]['elements'][$uid]['datetime'] = $row['tstamp'];
            $noData = false;
        }
        
        // Dokumente rendern
        if (count($dataArray)>0) {
            foreach ($foundPids as $pid) {
                foreach ($dataArray[$pid]['elements'] as $uid => $value) {
                    $dataArray[$pid]['elements'][$uid]['bodytext'] = $this->myRender('tt_content', $uid);
                }
            }
        }

        $this->view->assign('pages', $dataArray);
        $this->view->assign('nodata', $noData);
        $this->view->assign('uid', $this->cObj->data['uid']);
        $this->view->assign('pids', $pidsArray);
        return $this->htmlResponse();
    }

    /**
     * action pages + UI
     *
     * @return ResponseInterface  */
    public function pages_ui_accordionAction(): ResponseInterface
    {
        $this->pagesAction();
        return $this->htmlResponse();
    }

    /**
     * action news
     *
     * @return ResponseInterface  */
    public function newsAction(): ResponseInterface
    {
        $pids = $this->getPidAndInit();
        $languageAspect = GeneralUtility::makeInstance(Context::class)->getAspect('language');
        $sys_language_uid = intval($languageAspect->getId());
        $pidsArray = explode(',', $pids);
        $dataArray = [];
        $noData = true;
        $order = ($this->settings['flexform']['sortorder']=='desc') ? 'DESC' : 'ASC';
        $mode = ($this->settings['flexform']['sortMode']=='1') ? true : false;
        if ($mode) {
            $pidsForeach = $pidsArray;
        } else {
            $pidsForeach = [$pids];
        }

        foreach ($pidsForeach as $pid) {
            // News+Kategorien holen
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_news_domain_model_news');
            $statement = $queryBuilder
            ->select('tx_news_domain_model_news.uid AS newsid', 'tx_news_domain_model_news.title AS newstitle', 'tx_news_domain_model_news.datetime AS newsdate',
                'bodytext', 'cat.uid AS catid', 'cat.title AS cattitle')
            ->from('tx_news_domain_model_news')
            ->leftJoin(
                'tx_news_domain_model_news',
                'sys_category_record_mm',
                'mm',
                $queryBuilder->expr()->eq(
                    'mm.uid_foreign',
                    $queryBuilder->quoteIdentifier('tx_news_domain_model_news.uid')
                )
            )
            ->leftJoin(
                'mm',
                'sys_category',
                'cat',
                $queryBuilder->expr()->eq(
                    'mm.uid_local',
                    $queryBuilder->quoteIdentifier('cat.uid')
                )
            );
            
            if ($mode) {
                $queryBuilder->where(
                    $queryBuilder->expr()->eq('tx_news_domain_model_news.pid', $queryBuilder->createNamedParameter(intval($pid), Connection::PARAM_INT))
                );
            } else {
                $queryBuilder->where(
                    $queryBuilder->expr()->in('tx_news_domain_model_news.pid', $queryBuilder->createNamedParameter($pidsArray, Connection::PARAM_INT_ARRAY))
                );
            }
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq('tx_news_domain_model_news.sys_language_uid', $queryBuilder->createNamedParameter($sys_language_uid, Connection::PARAM_INT))
            );
            $queryBuilder->orderBy('cattitle', $order)->addOrderBy('tx_news_domain_model_news.datetime', $order);
            //debug($queryBuilder->getSQL());
            $statement = $queryBuilder->executeQuery();
            
            while ($row = $statement->fetchAssociative()) {
                if (isset($row['catid']) && !isset($dataArray[$row['catid']])) {
                    $dataArray[$row['catid']] = [];
                    $dataArray[$row['catid']]['news'] = [];
                }
                $dataArray[$row['catid']]['header'] = $row['cattitle'];
                $dataArray[$row['catid']]['news'][$row['newsid']] = [];
                $dataArray[$row['catid']]['news'][$row['newsid']]['header'] = $row['newstitle'];
                $dataArray[$row['catid']]['news'][$row['newsid']]['datetime'] = $row['newsdate'];
                $dataArray[$row['catid']]['news'][$row['newsid']]['bodytext'] = $row['bodytext'];
                $noData = false;
            }
        }

        $this->view->assign('elements', $dataArray);
        $this->view->assign('nodata', $noData);
        $this->view->assign('childs', count($dataArray));
        $this->view->assign('uid', $this->cObj->data['uid']);
        $this->view->assign('pids', $pidsArray);
        return $this->htmlResponse();
    }

    /**
     * action news + UI
     *
     * @return ResponseInterface  */
    public function news_ui_accordionAction(): ResponseInterface
    {
        $this->newsAction();
        return $this->htmlResponse();
    }
}
