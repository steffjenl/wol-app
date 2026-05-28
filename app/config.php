<?php

define('TARGET_MAC',    getenv('TARGET_MAC')    ?: '0C:9D:92:81:FE:60');
define('TARGET_IP',     getenv('TARGET_IP')     ?: '192.168.24.3');
define('BROADCAST_IP',  getenv('BROADCAST_IP')  ?: '192.168.24.255');
define('APP_USERNAME',  getenv('APP_USERNAME')  ?: 'admin');
define('APP_PASSWORD_HASH', getenv('APP_PASSWORD_HASH') ?: '');
define('SESSION_TIMEOUT', 3600);

function wol_devices(): array {
	$devices = [
		[
			'id'   => 'windows-pc',
			'name' => getenv('TARGET_NAME') ?: 'Windows 11 PC',
			'ip'   => TARGET_IP,
			'mac'  => TARGET_MAC,
		],
	];

	$diskStationMac = trim((string) (getenv('DISKSTATION_MAC') ?: ''));
	$diskStationIp  = trim((string) (getenv('DISKSTATION_IP') ?: ''));

	if ($diskStationMac !== '' && $diskStationIp !== '') {
		$devices[] = [
			'id'   => 'diskstation',
			'name' => getenv('DISKSTATION_NAME') ?: 'DiskStation',
			'ip'   => $diskStationIp,
			'mac'  => $diskStationMac,
		];
	}

	return $devices;
}
