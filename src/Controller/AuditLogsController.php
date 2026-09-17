<?php
declare(strict_types=1);
namespace App\Controller;
/**
 * AuditLogs Controller
 *
 * @property \App\Model\Table\AuditLogsTable $AuditLogs
 * @method \App\Model\Entity\AuditLog[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AuditLogsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $auditLogs = $this->AuditLogs
            ->find()
            ->contain(['Users'])
            ->order([
                'AuditLogs.created' => 'DESC'
            ]);

        $this->set(compact('auditLogs'));
    }
    
    public function downloadSummary()
    {
        /*
        |--------------------------------------------------------------------------
        | GET ALL AUDIT LOGS
        |--------------------------------------------------------------------------
        */
    
        $auditLogs = $this->AuditLogs->find()
            ->contain([
                'Users'
            ])
            ->order([
                'AuditLogs.created' => 'DESC'
            ])
            ->all();
    
    
        /*
        |--------------------------------------------------------------------------
        | CREATE SPREADSHEET
        |--------------------------------------------------------------------------
        */
    
        $spreadsheet =
            new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    
    
        /*
        |--------------------------------------------------------------------------
        | SUMMARY SHEET
        |--------------------------------------------------------------------------
        */
    
        $summarySheet =
            $spreadsheet->getActiveSheet();
    
        $summarySheet->setTitle('Summary');
    
    
        /*
        |--------------------------------------------------------------------------
        | COUNT DATA
        |--------------------------------------------------------------------------
        */
    
        $totalLogs = 0;
    
        $users = [];
    
        $actions = [];
    
        $subjects = [];
    
    
        foreach ($auditLogs as $log) {
    
            $totalLogs++;
    
    
            /*
            |--------------------------------------------------------------------------
            | USER COUNT
            |--------------------------------------------------------------------------
            */
    
            $username = 'Unknown User';
    
            if (
                !empty($log->user) &&
                !empty($log->user->username)
            ) {
    
                $username =
                    trim(
                        (string)$log->user->username
                    );
            }
    
            $users[$username] =
                ($users[$username] ?? 0) + 1;
    
    
            /*
            |--------------------------------------------------------------------------
            | ACTION COUNT
            |--------------------------------------------------------------------------
            */
    
            $action = trim(
                (string)($log->action ?? '')
            );
    
            if ($action === '') {
                $action = 'Unknown';
            }
    
            $actions[$action] =
                ($actions[$action] ?? 0) + 1;
    
    
            /*
            |--------------------------------------------------------------------------
            | SUBJECT COUNT
            |--------------------------------------------------------------------------
            */
    
            $subject = trim(
                (string)($log->subject_type ?? '')
            );
    
            if ($subject === '') {
                $subject = 'N/A';
            }
    
            $subjects[$subject] =
                ($subjects[$subject] ?? 0) + 1;
        }
    
    
        /*
        |--------------------------------------------------------------------------
        | SORT COUNTS
        |--------------------------------------------------------------------------
        */
    
        arsort($users);
    
        arsort($actions);
    
        arsort($subjects);
    
    
        /*
        |--------------------------------------------------------------------------
        | SUMMARY TITLE
        |--------------------------------------------------------------------------
        */
    
        $summarySheet->setCellValue(
            'A1',
            'AUDIT LOG SUMMARY'
        );
    
        $summarySheet
            ->mergeCells('A1:D1');
    
    
        /*
        |--------------------------------------------------------------------------
        | SUMMARY TITLE STYLE
        |--------------------------------------------------------------------------
        */
    
        $titleStyle =
            $summarySheet->getStyle('A1:D1');
    
        $titleStyle
            ->getFont()
            ->setBold(true)
            ->setSize(16);
    
        $titleStyle
            ->getAlignment()
            ->setHorizontal(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            );
    
        $titleStyle
            ->getAlignment()
            ->setVertical(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            );
    
        $summarySheet
            ->getRowDimension(1)
            ->setRowHeight(30);
    
    
        /*
        |--------------------------------------------------------------------------
        | GENERATED DATE
        |--------------------------------------------------------------------------
        */
    
        $summarySheet->setCellValue(
            'A3',
            'Generated Date'
        );
    
        $summarySheet->setCellValue(
            'B3',
            date('F d, Y h:i A')
        );
    
    
        /*
        |--------------------------------------------------------------------------
        | TOTAL LOGS
        |--------------------------------------------------------------------------
        */
    
        $summarySheet->setCellValue(
            'A4',
            'Total Audit Logs'
        );
    
        $summarySheet->setCellValue(
            'B4',
            $totalLogs
        );
    
    
        /*
        |--------------------------------------------------------------------------
        | UNIQUE USERS
        |--------------------------------------------------------------------------
        */
    
        $summarySheet->setCellValue(
            'A5',
            'Unique Users'
        );
    
        $summarySheet->setCellValue(
            'B5',
            count($users)
        );
    
    
        /*
        |--------------------------------------------------------------------------
        | UNIQUE ACTIONS
        |--------------------------------------------------------------------------
        */
    
        $summarySheet->setCellValue(
            'A6',
            'Unique Actions'
        );
    
        $summarySheet->setCellValue(
            'B6',
            count($actions)
        );
    
    
        /*
        |--------------------------------------------------------------------------
        | UNIQUE SUBJECTS
        |--------------------------------------------------------------------------
        */
    
        $summarySheet->setCellValue(
            'A7',
            'Unique Subjects'
        );
    
        $summarySheet->setCellValue(
            'B7',
            count($subjects)
        );
    
    
        /*
        |--------------------------------------------------------------------------
        | SUMMARY LABEL STYLE
        |--------------------------------------------------------------------------
        */
    
        $summarySheet
            ->getStyle('A3:A7')
            ->getFont()
            ->setBold(true);
    
    
        /*
        |--------------------------------------------------------------------------
        | ACTION SUMMARY
        |--------------------------------------------------------------------------
        */
    
        $summarySheet->setCellValue(
            'A10',
            'ACTION SUMMARY'
        );
    
        $summarySheet
            ->mergeCells('A10:B10');
    
    
        $summarySheet->setCellValue(
            'A11',
            'Action'
        );
    
        $summarySheet->setCellValue(
            'B11',
            'Count'
        );
    
    
        $actionRow = 12;
    
    
        foreach ($actions as $action => $count) {
    
            $actionLabel = ucwords(
                str_replace(
                    '_',
                    ' ',
                    $action
                )
            );
    
            $summarySheet->setCellValue(
                'A' . $actionRow,
                $actionLabel
            );
    
            $summarySheet->setCellValue(
                'B' . $actionRow,
                $count
            );
    
            $actionRow++;
        }
    
    
        /*
        |--------------------------------------------------------------------------
        | USER SUMMARY
        |--------------------------------------------------------------------------
        */
    
        $userStartRow =
            max(
                $actionRow + 3,
                12
            );
    
    
        $summarySheet->setCellValue(
            'A' . $userStartRow,
            'USER SUMMARY'
        );
    
        $summarySheet
            ->mergeCells(
                'A' . $userStartRow .
                ':B' . $userStartRow
            );
    
    
        $userHeaderRow =
            $userStartRow + 1;
    
    
        $summarySheet->setCellValue(
            'A' . $userHeaderRow,
            'User'
        );
    
        $summarySheet->setCellValue(
            'B' . $userHeaderRow,
            'Activity Count'
        );
    
    
        $userRow =
            $userHeaderRow + 1;
    
    
        foreach ($users as $username => $count) {
    
            $summarySheet->setCellValue(
                'A' . $userRow,
                $username
            );
    
            $summarySheet->setCellValue(
                'B' . $userRow,
                $count
            );
    
            $userRow++;
        }
    
    
        /*
        |--------------------------------------------------------------------------
        | SUBJECT SUMMARY
        |--------------------------------------------------------------------------
        */
    
        $subjectStartRow =
            max(
                $userRow + 3,
                $actionRow + 3
            );
    
    
        $summarySheet->setCellValue(
            'A' . $subjectStartRow,
            'SUBJECT SUMMARY'
        );
    
        $summarySheet
            ->mergeCells(
                'A' . $subjectStartRow .
                ':B' . $subjectStartRow
            );
    
    
        $subjectHeaderRow =
            $subjectStartRow + 1;
    
    
        $summarySheet->setCellValue(
            'A' . $subjectHeaderRow,
            'Subject'
        );
    
        $summarySheet->setCellValue(
            'B' . $subjectHeaderRow,
            'Activity Count'
        );
    
    
        $subjectRow =
            $subjectHeaderRow + 1;
    
    
        foreach ($subjects as $subject => $count) {
    
            $summarySheet->setCellValue(
                'A' . $subjectRow,
                $subject
            );
    
            $summarySheet->setCellValue(
                'B' . $subjectRow,
                $count
            );
    
            $subjectRow++;
        }
    
    
        /*
        |--------------------------------------------------------------------------
        | SUMMARY HEADER STYLE
        |--------------------------------------------------------------------------
        */
    
        $summaryHeaderRanges = [
            'A11:B11',
            'A' . $userHeaderRow . ':B' . $userHeaderRow,
            'A' . $subjectHeaderRow . ':B' . $subjectHeaderRow
        ];
    
    
        foreach ($summaryHeaderRanges as $range) {
    
            $style =
                $summarySheet->getStyle($range);
    
    
            /*
            |--------------------------------------------------------------------------
            | BOLD
            |--------------------------------------------------------------------------
            */
    
            $style
                ->getFont()
                ->setBold(true);
    
    
            /*
            |--------------------------------------------------------------------------
            | YELLOW FILL
            |--------------------------------------------------------------------------
            */
    
            $style
                ->getFill()
                ->setFillType(
                    \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID
                );
    
            $style
                ->getFill()
                ->getStartColor()
                ->setARGB('FFFFFF00');
    
            $style
                ->getFill()
                ->getEndColor()
                ->setARGB('FFFFFF00');
    
    
            /*
            |--------------------------------------------------------------------------
            | ALIGNMENT
            |--------------------------------------------------------------------------
            */
    
            $style
                ->getAlignment()
                ->setHorizontal(
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                );
    
            $style
                ->getAlignment()
                ->setVertical(
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                );
    
    
            /*
            |--------------------------------------------------------------------------
            | BORDERS
            |--------------------------------------------------------------------------
            */
    
            $style
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(
                    \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                )
                ->getColor()
                ->setARGB('808080');
        }
    
    
        /*
        |--------------------------------------------------------------------------
        | SUMMARY SECTION HEADERS
        |--------------------------------------------------------------------------
        */
    
        $sectionRanges = [
            'A10:B10',
            'A' . $userStartRow . ':B' . $userStartRow,
            'A' . $subjectStartRow . ':B' . $subjectStartRow
        ];
    
    
        foreach ($sectionRanges as $range) {
    
            $style =
                $summarySheet->getStyle($range);
    
    
            $style
                ->getFont()
                ->setBold(true)
                ->setSize(12);
    
    
            $style
                ->getAlignment()
                ->setHorizontal(
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT
                );
        }
    
    
        /*
        |--------------------------------------------------------------------------
        | SUMMARY BORDERS
        |--------------------------------------------------------------------------
        */
    
        $summaryLastRow =
            max(
                $actionRow - 1,
                $userRow - 1,
                $subjectRow - 1
            );
    
    
        $summarySheet
            ->getStyle(
                'A3:B' . $summaryLastRow
            )
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(
                \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            )
            ->getColor()
            ->setARGB('D9D9D9');
    
    
        /*
        |--------------------------------------------------------------------------
        | SUMMARY COLUMN WIDTHS
        |--------------------------------------------------------------------------
        */
    
        $summarySheet
            ->getColumnDimension('A')
            ->setWidth(30);
    
        $summarySheet
            ->getColumnDimension('B')
            ->setWidth(20);
    
        $summarySheet
            ->getColumnDimension('C')
            ->setWidth(20);
    
        $summarySheet
            ->getColumnDimension('D')
            ->setWidth(20);
    
    
        /*
        |--------------------------------------------------------------------------
        | AUDIT LOGS SHEET
        |--------------------------------------------------------------------------
        */
    
        $auditSheet =
            $spreadsheet->createSheet();
    
        $auditSheet->setTitle('Audit Logs');
    
    
        /*
        |--------------------------------------------------------------------------
        | AUDIT LOG HEADERS
        |--------------------------------------------------------------------------
        */
    
        $headers = [
            'ID',
            'User',
            'Action',
            'Description',
            'Subject',
            'Subject ID',
            'IP Address',
            'Date'
        ];
    
    
        foreach ($headers as $index => $header) {
    
            $column =
                \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                    $index + 1
                );
    
            $auditSheet->setCellValue(
                $column . '1',
                $header
            );
        }
    
    
        /*
        |--------------------------------------------------------------------------
        | INSERT AUDIT LOG DATA
        |--------------------------------------------------------------------------
        */
    
        $rowNumber = 2;
    
    
        foreach ($auditLogs as $log) {
    
            /*
            |--------------------------------------------------------------------------
            | USER
            |--------------------------------------------------------------------------
            */
    
            $username = 'Unknown User';
    
            if (
                !empty($log->user) &&
                !empty($log->user->username)
            ) {
    
                $username =
                    trim(
                        (string)$log->user->username
                    );
            }
    
    
            /*
            |--------------------------------------------------------------------------
            | ACTION
            |--------------------------------------------------------------------------
            */
    
            $action = trim(
                (string)($log->action ?? '')
            );
    
            if ($action === '') {
                $action = 'N/A';
            }
    
    
            /*
            |--------------------------------------------------------------------------
            | DESCRIPTION
            |--------------------------------------------------------------------------
            */
    
            $description = trim(
                (string)($log->description ?? '')
            );
    
            if ($description === '') {
                $description = 'N/A';
            }
    
    
            /*
            |--------------------------------------------------------------------------
            | SUBJECT
            |--------------------------------------------------------------------------
            */
    
            $subject = trim(
                (string)($log->subject_type ?? '')
            );
    
            if ($subject === '') {
                $subject = 'N/A';
            }
    
    
            /*
            |--------------------------------------------------------------------------
            | SUBJECT ID
            |--------------------------------------------------------------------------
            */
    
            $subjectId = '';
    
            if (
                $log->subject_id !== null &&
                $log->subject_id !== ''
            ) {
    
                $subjectId =
                    (string)$log->subject_id;
            }
    
    
            /*
            |--------------------------------------------------------------------------
            | IP ADDRESS
            |--------------------------------------------------------------------------
            */
    
            $ipAddress = trim(
                (string)($log->ip_address ?? '')
            );
    
            if ($ipAddress === '') {
                $ipAddress = 'N/A';
            }
    
    
            /*
            |--------------------------------------------------------------------------
            | DATE
            |--------------------------------------------------------------------------
            */
    
            $created = '';
    
            if (!empty($log->created)) {
    
                if (
                    $log->created
                    instanceof \DateTimeInterface
                ) {
    
                    $created =
                        $log->created->format(
                            'Y-m-d h:i A'
                        );
    
                } else {
    
                    $timestamp =
                        strtotime(
                            (string)$log->created
                        );
    
                    if ($timestamp !== false) {
    
                        $created =
                            date(
                                'Y-m-d h:i A',
                                $timestamp
                            );
                    }
                }
            }
    
            if ($created === '') {
                $created = 'N/A';
            }
    
    
            /*
            |--------------------------------------------------------------------------
            | WRITE DATA
            |--------------------------------------------------------------------------
            */
    
            $auditSheet->setCellValue(
                'A' . $rowNumber,
                $log->id
            );
    
    
            $auditSheet->setCellValue(
                'B' . $rowNumber,
                $username
            );
    
    
            $auditSheet->setCellValue(
                'C' . $rowNumber,
                ucwords(
                    str_replace(
                        '_',
                        ' ',
                        $action
                    )
                )
            );
    
    
            $auditSheet->setCellValue(
                'D' . $rowNumber,
                $description
            );
    
    
            $auditSheet->setCellValue(
                'E' . $rowNumber,
                $subject
            );
    
    
            $auditSheet->setCellValueExplicit(
                'F' . $rowNumber,
                $subjectId,
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );
    
    
            $auditSheet->setCellValueExplicit(
                'G' . $rowNumber,
                $ipAddress,
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );
    
    
            $auditSheet->setCellValue(
                'H' . $rowNumber,
                $created
            );
    
    
            $rowNumber++;
        }
    
    
        /*
        |--------------------------------------------------------------------------
        | AUDIT LOG LAST ROW
        |--------------------------------------------------------------------------
        */
    
        $lastRow =
            max(
                1,
                $rowNumber - 1
            );
    
    
        /*
        |--------------------------------------------------------------------------
        | COLUMN WIDTHS
        |--------------------------------------------------------------------------
        */
    
        $widths = [
            'A' => 10,
            'B' => 22,
            'C' => 20,
            'D' => 55,
            'E' => 25,
            'F' => 15,
            'G' => 20,
            'H' => 24
        ];
    
    
        foreach ($widths as $column => $width) {
    
            $auditSheet
                ->getColumnDimension($column)
                ->setWidth($width);
        }
    
    
        /*
        |--------------------------------------------------------------------------
        | AUDIT LOG HEADER STYLE
        |--------------------------------------------------------------------------
        */
    
        $headerStyle =
            $auditSheet->getStyle('A1:H1');
    
    
        /*
        |--------------------------------------------------------------------------
        | HEADER FONT
        |--------------------------------------------------------------------------
        */
    
        $headerStyle
            ->getFont()
            ->setBold(true)
            ->setSize(11)
            ->getColor()
            ->setARGB('FF000000');
    
    
        /*
        |--------------------------------------------------------------------------
        | HEADER YELLOW FILL
        |--------------------------------------------------------------------------
        */
    
        $headerStyle
            ->getFill()
            ->setFillType(
                \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID
            );
    
    
        $headerStyle
            ->getFill()
            ->getStartColor()
            ->setARGB('FFFFFF00');
    
    
        $headerStyle
            ->getFill()
            ->getEndColor()
            ->setARGB('FFFFFF00');
    
    
        /*
        |--------------------------------------------------------------------------
        | HEADER ALIGNMENT
        |--------------------------------------------------------------------------
        */
    
        $headerStyle
            ->getAlignment()
            ->setHorizontal(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            );
    
    
        $headerStyle
            ->getAlignment()
            ->setVertical(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            );
    
    
        $headerStyle
            ->getAlignment()
            ->setWrapText(true);
    
    
        /*
        |--------------------------------------------------------------------------
        | HEADER BORDERS
        |--------------------------------------------------------------------------
        */
    
        $headerStyle
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(
                \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            )
            ->getColor()
            ->setARGB('808080');
    
    
        /*
        |--------------------------------------------------------------------------
        | DATA BORDERS
        |--------------------------------------------------------------------------
        */
    
        if ($lastRow >= 2) {
    
            $auditSheet
                ->getStyle(
                    "A2:H{$lastRow}"
                )
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(
                    \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                )
                ->getColor()
                ->setARGB('D9D9D9');
    
    
            /*
            |--------------------------------------------------------------------------
            | VERTICAL ALIGNMENT
            |--------------------------------------------------------------------------
            */
    
            $auditSheet
                ->getStyle(
                    "A2:H{$lastRow}"
                )
                ->getAlignment()
                ->setVertical(
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                );
    
    
            /*
            |--------------------------------------------------------------------------
            | WRAP TEXT
            |--------------------------------------------------------------------------
            */
    
            $auditSheet
                ->getStyle(
                    "A2:H{$lastRow}"
                )
                ->getAlignment()
                ->setWrapText(true);
        }
    
    
        /*
        |--------------------------------------------------------------------------
        | HEADER ROW HEIGHT
        |--------------------------------------------------------------------------
        */
    
        $auditSheet
            ->getRowDimension(1)
            ->setRowHeight(30);
    
    
        /*
        |--------------------------------------------------------------------------
        | FREEZE HEADER
        |--------------------------------------------------------------------------
        */
    
        $auditSheet->freezePane('A2');
    
    
        /*
        |--------------------------------------------------------------------------
        | AUTO FILTER
        |--------------------------------------------------------------------------
        */
    
        $auditSheet->setAutoFilter(
            "A1:H{$lastRow}"
        );
    
    
        /*
        |--------------------------------------------------------------------------
        | PAGE SETUP
        |--------------------------------------------------------------------------
        */
    
        $auditSheet
            ->getPageSetup()
            ->setOrientation(
                \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE
            );
    
    
        $auditSheet
            ->getPageSetup()
            ->setPaperSize(
                \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4
            );
    
    
        $auditSheet
            ->getPageSetup()
            ->setFitToWidth(1);
    
    
        $auditSheet
            ->getPageSetup()
            ->setFitToHeight(0);
    
    
        $auditSheet
            ->getPageSetup()
            ->setFitToPage(true);
    
    
        /*
        |--------------------------------------------------------------------------
        | PAGE MARGINS
        |--------------------------------------------------------------------------
        */
    
        $auditSheet
            ->getPageMargins()
            ->setTop(0.25);
    
        $auditSheet
            ->getPageMargins()
            ->setRight(0.25);
    
        $auditSheet
            ->getPageMargins()
            ->setBottom(0.25);
    
        $auditSheet
            ->getPageMargins()
            ->setLeft(0.25);
    
    
        /*
        |--------------------------------------------------------------------------
        | REPEAT HEADER WHEN PRINTING
        |--------------------------------------------------------------------------
        */
    
        $auditSheet
            ->getPageSetup()
            ->setRowsToRepeatAtTopByStartAndEnd(
                1,
                1
            );
    
    
        /*
        |--------------------------------------------------------------------------
        | FILE NAME
        |--------------------------------------------------------------------------
        */
    
        $filename =
            'audit_log_summary_' .
            date('Y-m-d_H-i-s') .
            '.xlsx';
    
    
        /*
        |--------------------------------------------------------------------------
        | CREATE WRITER
        |--------------------------------------------------------------------------
        */
    
        $writer =
            new \PhpOffice\PhpSpreadsheet\Writer\Xlsx(
                $spreadsheet
            );
    
    
        /*
        |--------------------------------------------------------------------------
        | TEMPORARY FILE
        |--------------------------------------------------------------------------
        */
    
        $tempFile =
            tempnam(
                sys_get_temp_dir(),
                'audit_log_summary_'
            );
    
    
        /*
        |--------------------------------------------------------------------------
        | SAVE
        |--------------------------------------------------------------------------
        */
    
        $writer->save($tempFile);
    
    
        /*
        |--------------------------------------------------------------------------
        | RETURN DOWNLOAD
        |--------------------------------------------------------------------------
        */
    
        return $this->response
            ->withType(
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            )
            ->withDownload($filename)
            ->withFile(
                $tempFile,
                [
                    'download' => true,
                    'name' => $filename
                ]
            );
    }
}
