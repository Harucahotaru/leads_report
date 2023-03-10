<?php

declare(strict_types=1);

namespace app\controllers;

use app\exceptions\DatabaseException;
use app\helpers\MySqlDatabaseHelper;

class LeadsReport
{
    const REPORTS_PATH = __DIR__ . '/../../reports/report.csv';

    /**
     * @return array
     *
     * @throws DatabaseException
     */
    public static function getPartners(): array
    {
        $db = new MySqlDatabaseHelper();
        $partners = $db->executeQuery('select description, mid  from api_users where mid is not null');

        if (empty($partners)) {
            throw new DatabaseException('Партнеры не найдены');
        }

        return $partners;
    }

    /**
     * @throws DatabaseException
     */
    public function createReport()
    {
        $result = [
            'error' => '',
            'file' => '',
        ];
        if (isset($_POST['partners']) && isset($_POST['quantity'])) {

            if (empty($_POST['date']['up']) || empty($_POST['date']['to'])) {
                $dates = $this->getDefaultDates();
            }else {
                $dates = $_POST['date'];
            }

            $leads = $this->getLeads($_POST['partners'], $_POST['quantity'], $dates);

            if (empty($leads)) {
                $result['error'] = 'Лиды не найдены';
            } else {
                $result['file'] = $this->formReportFile($leads);
                $result['message'] = "Найдено " . count($leads) . " лидов";

            }
        } else {
            $result['error'] = 'Заполните форму';
        }

        echo json_encode($result);
    }

    /**
     * @param array $partners
     * @param string $quantity
     * @param array $dates
     *
     * @return array
     *
     * @throws DatabaseException
     */
    private function getLeads(array $partners, string $quantity, array $dates): array
    {
        $leads = [];

        $db = new MySqlDatabaseHelper();

        $sql = 'select * from (';
        foreach ($partners as $key => $partner) {
            $sql .= "select date_create ,eo.mid,eo.id from eosago_orders eo, api_users au where au.parent_id = eo.user_id  and au.mid = " . "'{$partner}'" . " and eo.date_create  > " . "'" . $dates['up'] . "'" . " and eo.date_create < " . "'" . $dates['to'] . "'" . " and status<>2";

            if ($key !== array_key_last($partners)) {
                $sql .= ' union ';
            }
        }
        $sql .= " union select eali.dt,eali.query_id,null  from eosago_api_log eali  where dt between " . "'" . $dates['up'] . "'" .  " and " . "'" . $dates['to'] . "'" . " and apikey='e6f1752e29349ba667b73474147f3508'  limit " . $quantity;
        $sql .= ') q limit ' . $quantity;

        $leads = $db->executeQuery($sql);

        if (empty($partners)) {
            throw new DatabaseException('Лиды не найдены');
        }

        return $leads;
    }

    /**
     * @param array $leads
     *
     * @return string
     */
    private function formReportFile(array $leads): string
    {
        $file = fopen(self::REPORTS_PATH, 'w');

        fputcsv($file, ['date_create', 'mid', 'id'], ';');

        foreach ($leads as $lead) {
            fputcsv($file, [$lead->date_create, $lead->mid, $lead->id], ';');
        }

        fclose($file);

        return '/reports/report.csv';
    }

    /**
     * @return array
     */
    private function getDefaultDates(): array
    {
        return [
            'up' =>  date('Y-m-01'),
            'to' => date('Y-m-31'),
        ];
    }
}