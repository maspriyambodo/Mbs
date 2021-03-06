<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_Lapbulanan extends CI_Model {

    function MReportMonthly() {
        if (date("F") == "JANUARY") {
            $between = date("Y", strtotime("-1 year")) . '-12-25';
            $between1 = date("Y") . '-01-24';
        } else {
            $between = date("Y") . '-' . date("m", strtotime("-1 month")) . '-25';
            $between1 = date("Y") . '-' . date("m") . '-24';
        }
        $exec = $this->db->select('mst_karyawan.nama_karyawan AS uname,mst_karyawan.lokasi_kerja,date_format( mst_karyawan.tanggal_masuk, "%m/%y" ) AS tanggal_masuk')
                ->select('( SELECT count( lap_interaksi.nom_tb) FROM lap_interaksi WHERE usr_adm.nik = lap_interaksi.nik AND lap_interaksi.syscreatedate BETWEEN "' . date("y") . '-' . date("m", strtotime("- 1 month ")) . '-25" and "' . date("y") . '-' . date("m") . '-24" AND lap_interaksi.nom_tb > 0 ) AS plaf')
                ->select('( SELECT count(*) FROM lap_rencana WHERE usr_adm.nik = lap_rencana.nik AND syscreatedate BETWEEN "' . date("y") . '-' . date("m", strtotime("- 1 month ")) . '-25" and "' . date("y") . '-' . date("m") . '-24") AS ri')
                ->select('( SELECT count( * ) FROM lap_interaksi WHERE usr_adm.nik = lap_interaksi.nik AND lap_interaksi.syscreatedate BETWEEN "' . date("y") . '-' . date("m", strtotime("- 1 month ")) . '-25" and "' . date("y") . '-' . date("m") . '-24" ) AS hi')
                ->select('( SELECT count( hp_status) FROM lap_interaksi WHERE usr_adm.nik = lap_interaksi.nik AND lap_interaksi.hp_status = "y" AND lap_interaksi.syscreatedate BETWEEN "' . date("y") . '-' . date("m", strtotime("- 1 month ")) . '-25" and "' . date("y") . '-' . date("m") . '-24" ) AS hp')
                ->from('usr_adm')
                ->join('mst_karyawan', 'usr_adm.nik = mst_karyawan.nik', 'LEFT')
                ->where(['usr_adm.hak_akses' => 10, 'mst_karyawan.`status`' => 1])
                ->group_by('usr_adm.nik')
                ->order_by('usr_adm.nik')
                ->get()
                ->result();
        return $exec;
    }

    function ReportMonthly($nik) {
        if (date("F") == "JANUARY") {
            $between = date("Y", strtotime("-1 year")) . '-12-25';
            $between1 = date("Y") . '-01-24';
        } else {
            $between = date("Y") . '-' . date("m", strtotime("-1 month")) . '-25';
            $between1 = date("Y") . '-' . date("m") . '-24';
        }
        $exec = $this->db->query('select norut.norut, mst_karyawan.nama_karyawan as uname, mst_karyawan.lokasi_kerja, date_format(mst_karyawan.tanggal_masuk,"%m/%y") as tanggal_masuk, ( select count( nom_tb) from lap_interaksi where usr_adm.nik = lap_interaksi.nik and lap_interaksi.syscreatedate between "' . date("y") . '-' . date("m", strtotime("- 1 month ")) . '-25" and "' . date("y") . '-' . date("m") . '-24" ) as plaf,( select count( *) from lap_rencana where usr_adm.nik = lap_rencana.nik and syscreatedate between "' . date("y") . '-' . date("m", strtotime("- 1 month ")) . '-25" and "' . date("y") . '-' . date("m") . '-24" ) as ri, ( select count( * ) from lap_interaksi where usr_adm.nik = lap_interaksi.nik and lap_interaksi.syscreatedate between "' . date("y") . '-' . date("m", strtotime("- 1 month ")) . '-25" and "' . date("y") . '-' . date("m") . '-24" ) as hi, ( select count( hp_status ) from lap_interaksi where usr_adm.nik = lap_interaksi.nik and lap_interaksi.hp_status = "y" and lap_interaksi.syscreatedate between "' . date("y") . '-' . date("m", strtotime("- 1 month ")) . '-25" and "' . date("y") . '-' . date("m") . '-24" ) as hp from norut left join usr_adm on norut.nik = usr_adm.nik left join mst_karyawan on norut.nik = mst_karyawan.nik left join lap_interaksi on norut.nik = lap_interaksi.nik left join lap_rencana on norut.nik = lap_rencana.nik where norut.spv = ' . $nik . ' group by norut.nik order by norut.norut asc');
        return $exec;
    }

}
