<?php

namespace SecureLicense\Classes;

use PDO;

class AddLicense {
	protected $db;

	public function __construct() {
		global $sldb;
		$this->db = $sldb;
	}

	public function create_license( $domain, $end_date = '' ) {
		$start_date = date( 'Y.m.d', time() );;
		$end_date = str_replace( "-", ".", $end_date );
		$unlimited = !empty($end_date) ?  false :  true;
		if ( ! empty( $domain ) ) {
			$url = parse_url( $domain );
			if ( isset( $url['host'] ) ) {
				$url = explode( ".", $url["host"] );
			} elseif ( isset( $url['path'] ) ) {
				$url = explode( ".", $url["path"] );
			}
			if ( $url === false ) {
				return get_error_msg( "Geçersiz alan adı" );
			}
			if ( $url[0] == 'www' ) {
				array_shift( $url );
			}
			if ( ! isset( $url[1] ) ) {
				return get_error_msg( "Geçersiz alan adı" );
			}
			$new_url      = $url[0] . '.' . $url[1];
			$check_domain = $this->db->query( "SELECT sl_domain FROM sl_licenses", PDO::FETCH_ASSOC );
			if ( $check_domain->rowCount() ) {
				foreach ( $check_domain as $check ) {
					if ( $check['sl_domain'] == $new_url ) {
						return get_warring_msg( 'Lisans zaten mevcut' );
					}
				}
			}
			$insert = $this->db->prepare( "INSERT INTO sl_licenses  SET sl_domain = :domain , sl_start_date = :start_date , sl_end_date = :end_date , sl_activity = :activity" );
			$insert->execute( array(
				"domain"     => $new_url,
				"start_date" => $start_date,
				"end_date"   => $unlimited === true ? date('Y-m-d', strtotime('Closed On')): $end_date,
				"activity"   => 1
			) );
			if ( $insert->rowCount() ) {
				return array( "success" => true, "text" => get_success_msg( "Başarıyla Eklendi" ) );
			} else {
				return get_error_msg( "Eklenemedi" );
			}
		} else {
			return get_error_msg( 'Lütfen bir alan adı girin' );
		}
	}
}