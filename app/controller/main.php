<?php

require_once '../../config.php';
require_once MODELPATH.'hako-file.php';
require_once MODELPATH.'hako-cgi.php';

/**
 * 箱庭諸島 S.E
 * @author hiro <@hiro0218>
 */
class Main {
    public function execute() {
        $hako = new \Hako();
        $cgi  = new \Cgi();

        $cgi->parseInputData();
        $cgi->getCookies();

        // [CHK] データファイルがない
        if (!$hako->readIslands($cgi)) {
            HTML::header();
            HakoError::noDataFile();
            HTML::footer();
            exit();
        }
        // [CHK] ファイルロック失敗
        if (false == ($lock = Util::lock())) {
            exit();
        }
        $cgi->setCookies();

        if (strtolower($cgi->dataSet['DEVELOPEMODE'] ?? "") == "javascript") {
            $html = new HtmlMapJS();
            $com  = new MakeJS();
        } else {
            $html = new HtmlMap();
            $com  = new Make();
        }
        switch ($cgi->mode) {
            case "log":
                // $html = new HtmlTop(); [NOTE]いらない気配
                $html->header();
                $html->log();
                $html->footer();

                break;

            case "turn":
                $turn = new Turn();
                $html = new HtmlTop();
                $html->header();
                $turn->turnMain($hako, $cgi->dataSet);
                // ターン処理後、通常トップページ描画
                $html->main($hako, $cgi->dataSet);
                $html->footer();

                break;

            case "owner":
                $html->header();
                $html->owner($hako, $cgi->dataSet);
                $html->footer();

                break;

            case "command":
                $html->header();
                $com->commandMain($hako, $cgi->dataSet);
                $html->footer();

                break;

            case "new":
                $html->header();
                $com->newIsland($hako, $cgi->dataSet);
                $html->footer();

                break;

            case "comment":
                $html->header();
                $com->commentMain($hako, $cgi->dataSet);
                $html->footer();

                break;

            case "print":
                $html->header();
                $html->visitor($hako, $cgi->dataSet);
                $html->footer();

                break;

            case "targetView":
                $html->head();
                $html->printTarget($hako, $cgi->dataSet);
                //$html->footer();
                break;

            case "change":
                $html->header();
                $com->changeMain($hako, $cgi->dataSet);
                $html->footer();

                break;

            case "ChangeOwnerName":
                $html->header();
                $com->changeOwnerName($hako, $cgi->dataSet);
                $html->footer();

                break;

            case "conf":
                $html = new HtmlTop();
                $html->header();
                $html->register($hako, $cgi->dataSet);
                $html->footer();

                break;

            default:
                $html = new HtmlTop();
                $html->header();
                $html->main($hako, $cgi->dataSet);
                $html->footer();
        }
        Util::unlock($lock);
        exit();
    }
}
