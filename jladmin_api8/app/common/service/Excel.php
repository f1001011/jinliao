<?php

namespace app\common\service;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Excel
{

    /*
     * $field_title 键是数据库里的字段名
     * @param array $fields 字段, eg: ['字段1', '字段2'...]
     * @param string|null $filename 导出文件名
     * */
    public  function export(array $field_title = [], array $fields = [],array $data = [], string $filename = '123')
    {
        if (!is_array($fields)) {
            return false;
        }

        //首先我们先定义号每个字段的含义  键是数据库里的字段名  值是含义
//        $field_title = [
//            'id' => '编号',
//            'username' => '用户名',
//            'title' => '标题',
//            'desc' => '简介',
//            'content' => '内容'
//        ];

        $field_res = [];

        //然后根据所需导出的字段组成相应的结果集
        foreach ($fields as $k => $v) {
            $field_res[$k]['title'] = $field_title[$v];
            $field_res[$k]['field'] = $v;
        }

        //查询数据
        //$data = Db::name('user')->field($fields)->select()->toArray();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        // sheet title
        $sheet->setTitle($filename);
        $startRow = 1; // 数据起始行
        foreach ($data as $key => $value) {
            foreach ($field_res as $k => $v) {
                $column = Coordinate::stringFromColumnIndex($k + 1);
                if ($key == 0) {
                    $sheet->setCellValue($column . $startRow, $v['title']);
                    $sheet->getColumnDimension($column)->setWidth(20);
                }
                $i = $key + 2; //表格是从2开始的
                //dd($v);
                $sheet->setCellValue($column . $i, $value[$v['field']]);
            }
        }

        $filename = urlencode($filename) . '.xlsx';
        // make file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=$filename");
        header('Cache-Control: max-age=0');
        header('Pragma: public');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');

    }


}