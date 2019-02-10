<?php get_header(); ?>
    <main class="page-content">
        <div class="container-fluid p-0">
            <h3>Secure License</h3>
            <hr>
			<?php
			if ( isset( $delete ) && ! empty( $delete ) ) {
				echo $delete;
				header( "Refresh: 1.5; url=" . URL );
			}
			?>
            <div class="sl-info">
                <div class="card-list">
                    <div class="row cards">
                        <div class="col-xl-3 col-lg-4 mb-4">
                            <div class="card blue">
                                <div class="title">Toplam Lisans</div>
                                <i class="zmdi zmdi-upload"></i>
                                <div class="value"><?= ! empty( $licenses['total'] ) ? $licenses['total'] : "0" ?></div>
                                <div class="stat"><a href="<?= URL . "licenses/" ?>">Detaylar <i class="fas fa-chevron-right"></i></a></div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 mb-4">
                            <div class="card orange">
                                <div class="title">Lisansız Kullanımlar</div>
                                <i class="zmdi zmdi-download"></i>
                                <div class="value"><?= ! empty( $notice_total_licenses ) ? $notice_total_licenses : "0" ?></div>
                                <div class="stat"><a href="<?=URL.'notice-licenses/'?>">Detaylar <i class="fas fa-chevron-right"></i></a></div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 mb-4">
                            <div class="card red">
                                <div class="title">Süresi Dolan Lisanslar</div>
                                <i class="zmdi zmdi-download"></i>
                                <div class="value"><?= ! empty( $expired_total_licenses ) ? $expired_total_licenses : "0" ?></div>
                                <div class="stat"><a href="<?=URL.'expired-licenses/'?>">Detaylar <i class="fas fa-chevron-right"></i></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<?php if ( isset( $licenses ) && $licenses != false ): ?>
                <div class="sl-licenses-table">
                    <table class="mdl-data-table mdl-shadow--2dp col-xl-10 col-md-12 mx-auto">
                        <thead>
                        <tr>
                            <th class="mdl-data-table__cell--non-numeric">Alan Adları</th>
                            <th>Başlangıç Tarihi</th>
                            <th>Bitiş Tarihi</th>
                            <th>Kalan Gün</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
						<?php foreach ( $licenses['licenses'] as $license ): ?>
                            <tr>
                                <td class="mdl-data-table__cell--non-numeric"><?= $license["sl_domain"] ?></td>
                                <td><?= custom_date_format( $license["sl_start_date"], "Y.m.d" ) ?></td>
                                <td><?=  $license["sl_end_date"] == "1970-01-01 00:00:00" ? "Süresiz" : custom_date_format( $license["sl_end_date"], "Y.m.d" ) ?></td>
                                <td><?php
									if ( $license["sl_end_date"] == "1970-01-01 00:00:00") {
										echo "Süresiz";
									} else {
										$dateDiff = date_difference( date( "Y.m.d" ), custom_date_format( $license["sl_end_date"], "Y.m.d" ), "." );
										echo empty( $dateDiff ) ? "<div class='end-license'>Lisans Süresi Doldu</div>" : $dateDiff;
									}
									?></td>
                                <td>
                                    <a href="<?= URL . 'add-license/?edit=' . $license['id'] ?>"><i class="fas fa-pen-square fa-2x edit_license"></i></a>
                                    <a href="?delete=<?= $license['id'] ?>" onClick="confirm('Bu lisansı silmek istediğinizden emin misin?')"><i
                                                class="fas fa-times-circle fa-2x delete_license"></i></a>
                                </td>
                            </tr>
						<?php endforeach; ?>
                        </tbody>
                    </table>

                </div>
				<?php
				$paginate = pagination( $licenses['per_page'], $licenses['page'] );
				foreach ( $paginate as $write ) {
					echo $write;
				}
			endif;
			?>
            <div class="clearfix"></div>
        </div>
    </main>
<?php get_footer(); ?>