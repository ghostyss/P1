<?php

class PdfHelper {

    private $Data;
    private $Fdf;

    function __construct() {
        
    }

    public function FillForm($PdfPath, $TargetPath, $Data) {
        $this->Fdf = "%FDF-1.2\x0d%\xe2\xe3\xcf\xd3\x0d\x0a";
        $this->Fdf .= "1 0 obj\x0d<< ";
        $this->Fdf .= "\x0d/FDF << ";
        $this->Fdf .= "/Fields [ ";
        $this->Data = $this->BurstDots($Data);
        $this->forge_fdf_fields($this->Data, '', true);
        $this->Fdf .= "] \x0d";
        if ($PdfPath) {
            $this->Fdf .= "/F (" . $this->escape_pdf_string($PdfPath) . ") \x0d";
        }
        $this->Fdf .= ">> \x0d";
        $this->Fdf .= ">> \x0dendobj\x0d";
        $this->Fdf .= "trailer\x0d<<\x0d/Root 1 0 R \x0d\x0d>>\x0d";
        $this->Fdf .= "%%EOF\x0d\x0a";
        $FdfPath = '../temp/FdfHelper' . date('YmdHis') . '.fdf';
        file_put_contents($FdfPath, $this->Fdf);
        $exec = "pdftk " . $PdfPath . " fill_form " . $FdfPath . " output " . $TargetPath . ' 2>&1';
        $exec = shell_exec($exec);
        unlink($FdfPath);
        if (file_exists($TargetPath)) {
            return $TargetPath;
        } else {
            return 'Error : ' . $exec;
        }
    }

    private function BurstDots($fdf_data_old) {
        $fdf_data_new = array();
        foreach ($fdf_data_old as $key => $value) {
            $key_split = explode('.', (string) $key, 2);
            if (count($key_split) == 2) {
                if (!array_key_exists((string) ($key_split[0]), $fdf_data_new)) {
                    $fdf_data_new[(string) ($key_split[0])] = array();
                }
                if (gettype($fdf_data_new[(string) ($key_split[0])]) != 'array') {

                    $fdf_data_new[(string) ($key_split[0])] = array('' => $fdf_data_new[(string) ($key_split[0])]);
                }
                $fdf_data_new[(string) ($key_split[0])][(string) ($key_split[1])] = $value;
            } else {
                if (array_key_exists((string) ($key_split[0]), $fdf_data_new) &&
                        gettype($fdf_data_new[(string) ($key_split[0])]) == 'array') {
                    $fdf_data_new[(string) $key][''] = $value;
                } else {
                    $fdf_data_new[(string) $key] = $value;
                }
            }
        }
        foreach ($fdf_data_new as $key => $value) {
            if (gettype($value) == 'array') {
                $fdf_data_new[(string) $key] = $this->BurstDots($value); // recurse
            }
        }
        return $fdf_data_new;
    }

    private function forge_fdf_fields($Data, $accumulated_name, $strings_b) {
        if (0 < strlen($accumulated_name)) {
            $accumulated_name .= '.';
        }
        foreach ($Data as $key => $value) {
            $this->Fdf .= "<< ";
            if (gettype($value) == 'array') {
                $this->Fdf .= "/T (" . $this->escape_pdf_string((string) $key) . ") ";
                $this->Fdf .= "/Kids [ ";
                $this->forge_fdf_fields($value, $accumulated_name . (string) $key, $strings_b);
                $this->Fdf .= "] ";
            } else {
                $this->Fdf .= "/T (" . $this->escape_pdf_string((string) $key) . ") ";
                if ($strings_b) {
                    $this->Fdf .= "/V (" . $this->escape_pdf_string((string) $value) . ") ";
                } else {
                    $this->Fdf .= "/V /" . escape_pdf_name((string) $value) . " ";
                }
                $this->forge_fdf_fields_flags($accumulated_name . (string) $key);
            }
            $this->Fdf .= ">> \x0d";
        }
    }

    private function forge_fdf_fields_flags($field_name) {
        $this->Fdf .= "/ClrF 2 ";
        $this->Fdf .= "/ClrFf 1 ";
    }

    private function escape_pdf_string($ss) {
        $backslash = chr(0x5c);
        $ss_esc = '';
        $ss_len = strlen($ss);
        for ($ii = 0; $ii < $ss_len; ++$ii) {
            if (ord($ss[$ii]) == 0x28 || ord($ss[$ii]) == 0x29 || ord($ss[$ii]) == 0x5c) {
                $ss_esc .= $backslash . $ss[$ii];
            } else if (ord($ss[$ii]) < 32 || 126 < ord($ss[$ii])) {
                $ss_esc .= sprintf("\\%03o", ord($ss[$ii]));
            } else {
                $ss_esc .= $ss[$ii];
            }
        }
        return $ss_esc;
    }

}
