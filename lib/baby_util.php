<?php
namespace BabyUtil{
    function _orderByDateDesc($a, $b) {
        return filemtime($b) - filemtime($a);
    }

    function _orderByDateAsc($a, $b) {
        return filemtime($a) - filemtime($b);
    }


    /**
     * ファイルを日付順にソートして返却する。
     * @param $path string ワイルドカードでファイルパスを指定する
     * @param $order string desc,またはascを指定する。descを指定した場合、新しい順に返却する。
     * ascを指定した場合、古い順に返却する。
     * @return 日付でソートされたファイル名の配列。
     */
    function getfileLists($path, $order="desc") {
        $files = glob($path);
        if($order == "desc") {
            usort($files, "\BabyUtil\_orderByDateDesc");
        }
        else {
            usort($files, "\BabyUtil\_orderByDateAsc");
        }
        return $files;
    }
}

