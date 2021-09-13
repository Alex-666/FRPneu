<?php

class ControllerExtensionModuleBazaAuto extends Controller
{

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function index()
    {
        $basedir = (realpath(dirname(__FILE__))) . '/../../../../';
        $xml = simplexml_load_file($basedir . 'auto.xml');
        $translate = json_decode(file_get_contents($basedir . 'translate.json'), true);

        $this->db->query("TRUNCATE TABLE cars");

        foreach ($xml->item as $item) {
            $carid = (string)$item->carid;

            //marka - vendor
            foreach ($translate['marka'] as $key => $langue) {
                if ($item->marka == $key || strstr($item->marka, $key)) {
                    $marka = str_ireplace($key, $langue, (string)$item->marka);
                    break;
                } else {
                    $marka = (string)$item->marka;
                }

            }

            // model
            foreach ($translate['model'] as $key => $langue) {
                if ($item->model == $key || strstr($item->model, $key)) {
                    $model = str_ireplace($key, $langue, (string)$item->model);
                    break;
                } else {
                    $model = (string)$item->model;
                }
            }

            $kuzov = (string)$item->kuzov;

            //modification
            foreach ($translate['modification'] as $key => $langue) {
                if ($item->modification == $key || strstr($item->modification, $key)) {
                    $modification = str_ireplace($key, $langue, (string)$item->modification);
                    break;
                } else {
                    $modification = (string)$item->modification;
                }
            }


            $beginyear = (string)$item->beginyear;
            $endyear = (string)$item->endyear;
            $krepezh = (string)$item->krepezh;
            $krepezhraz = (string)$item->krepezhraz;
            $krepezhraz2 = (string)$item->krepezhraz2;
            $hole = (string)$item->hole;
            $pcd = (string)$item->pcd;
            $dia = (string)$item->dia;
            $diamax = (string)$item->diamax;
            $diski = [];
            foreach ($item->diski->beforewheels as $beforewheels) {
                $disk1 = [];
                $disk1[] = (string)$beforewheels->tyres->width;
                $disk1[] = (string)$beforewheels->tyres->height;
                $disk1[] = (string)$beforewheels->tyres->diameter;

                $disk2 = [];
                $disk2[] = (string)$beforewheels->width;
                $disk2[] = (string)$beforewheels->diameter;
                $disk2[] = (string)$beforewheels->et;
                $diski[] = [$disk1, $disk2];
            }
            $years = range($beginyear, $endyear);

            //$this->db->query("RENAME TABLE `cars` TO `cars_del`");
            /*if ($this->db->query("DESCRIBE cars_tmp")) {
                $this->db->query("DROP TABLE `cars_tmp`");
                $this->db->query("CREATE TABLE `cars_tmp` LIKE `cars`");
                $this->db->query("ALTER TABLE `cars_tmp` ENGINE=MyISAM");
                //die;
            }
            else {
                $this->db->query("CREATE TABLE `cars_tmp` LIKE `cars`");
                $this->db->query("ALTER TABLE `cars_tmp` ENGINE=MyISAM");
            }
            die;*/
            foreach ($years as $year) {

                $this->db->query("INSERT INTO `cars` (`vendor`, `model`, `year`, `modification`, `nut`, `pcd`, `dia`, `data`) VALUES ('" . substr($marka, 0, 12) . "', '" . substr($model, 0, 25) . "', " . $year . ", '" . substr($modification, 0, 29) . "', '" . substr($krepezhraz . 'X' . $krepezhraz2, 0, 7) . "', '" . substr($hole . 'X' . $pcd, 0, 8) . "', '" . substr($dia, 0, 5) . "', '" . json_encode($diski) . "')");

            }

        }

        //$check = $this->db->query("SELECT * FROM `cars` WHERE HEX(`modification`) REGEXP '^([0-7][
        //0-9A-F])*$'");

        echo "END";
    }
}
