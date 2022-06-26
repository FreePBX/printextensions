<?php
namespace FreePBX\modules\Printextensions;
use FreePBX\modules\Backup as Base;
class Backup Extends Base\BackupBase{
	public function runBackup($id,$transaction){
		$configs = [
			'kvstore' => $this->dumpKVStore()
		];
		$this->addConfigs($configs);
	}
}
