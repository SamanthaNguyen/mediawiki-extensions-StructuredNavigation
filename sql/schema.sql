CREATE TABLE /* */sn_nav (
	snn_id int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT
)/*$wgDBTableOptions*/;

CREATE TABLE /* */sn_page (
	snp_id int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
	FOREIGN KEY( snp_nav_id ) REFERENCES sn_nav( snn_id )
)/*$wgDBTableOptions*/;
