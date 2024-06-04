<?php
namespace LuxuryUnlimited\WorkerStores\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallData implements InstallDataInterface
{


    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        /**
         * \Magento\Framework\DB\Adapter\AdapterInterface
         */
        $conn = $setup->getConnection();

        $tableName = $setup->getTable('workers_store_mapping');

        /**
         *Inserting Worker one store mapper
         */
        $workerOneMapper = ['gb-en','bh-en','bh-ar','eg-en','eg-ar','jo-en','jo-ar','kw-en','kw-ar',
            'om-en','om-ar','qa-en','qa-ar','ae-en','ae-ar','al-en','am-en','aw-en','au-en'];

        foreach($workerOneMapper as $storeCode){
            $data = [
                'worker_name'=>'worker 1',
                'instance_name'=>'',
                'store_code'=>$storeCode,
            ];
            $conn->insert($tableName, $data);
        }

        /**
         * Inserting Worker two store mapper
         */
        $workerTwoMapper = ['bs-en','bb-en','by-en','bw-en','bg-en','cl-en','tw-en','cr-en','hr-en','cy-en','cz-en','cz-ru','dk-en'];

        foreach($workerTwoMapper as $storeCode){
            $data = [
                'worker_name'=>'worker 2',
                'instance_name'=>'',
                'store_code'=>$storeCode,
            ];
            $conn->insert($tableName, $data);
        }

        /**
         * Inserting Worker three store mapper
         */
        $workerThreeMapper = ['cn-en','cn-cn','hk-en','hk-cn','jp-en','jp-jp','kr-en','kr-kr','sv-en','sv-es','ee-en','fi-en','fr-en','fr-fr','ge-en'];

        foreach($workerThreeMapper as $storeCode){
            $data = [
                'worker_name'=>'worker 3',
                'instance_name'=>'',
                'store_code'=>$storeCode,
            ];
            $conn->insert($tableName, $data);
        }

        /**
         * Inserting Worker four store mapper
         */
        $workerFourMapper = ['gh-en','gr-en','gt-en','gt-es','hu-en','is-en','in-en','id-en','ie-en','il-en','it-en','it-it','kz-en','ke-en','lv-en','lt-en'];

        foreach($workerFourMapper as $storeCode){
            $data = [
                'worker_name'=>'worker 4',
                'instance_name'=>'',
                'store_code'=>$storeCode,
            ];
            $conn->insert($tableName, $data);
        }

        /**
         * Inserting Worker five store mapper
         */
        $workerFiveMapper = ['be-fr','be-en','ca-en','cn-fr','at-en','at-ge','de-en','de-ge','ru-en','ru-ru','ua-en','ua-ru','lu-en','mg-en','my-en','mv-en','mt-en','mu-en'];

        foreach($workerFiveMapper as $storeCode){
            $data = [
                'worker_name'=>'worker 5',
                'instance_name'=>'',
                'store_code'=>$storeCode,
            ];
            $conn->insert($tableName, $data);
        }

        /**
         * Inserting Worker six store mapper
         */
        $workerSixMapper = ['md-en','mn-en','ma-en','na-en','nl-en','nz-en','ni-en','ng-en','no-en','pk-en','pg-en','py-en','pe-en','ph-en','pl-en','pt-en'];

        foreach($workerSixMapper as $storeCode){
            $data = [
                'worker_name'=>'worker 6',
                'instance_name'=>'',
                'store_code'=>$storeCode,
            ];
            $conn->insert($tableName, $data);
        }

        /**
         * Inserting Worker seven store mapper
         */
        $workerSevenMapper = ['ar-en','ar-es','bo-en','bo-es','br-en','br-es','co-en','co-es','ec-en','ec-es','hn-en','hn-es','mx-en','mx-es','pa-en','pa-es','uy-en','uy-es','ve-en','ve-es','ro-en','rw-en','sa-en','sc-en','sg-en'];

        foreach($workerSevenMapper as $storeCode){
            $data = [
                'worker_name'=>'worker 7',
                'instance_name'=>'',
                'store_code'=>$storeCode,
            ];
            $conn->insert($tableName, $data);
        }

        /**
         * Inserting Worker eight store mapper
         */
        $workerEightMapper = ['sk-en','si-en','za-en','es-en','es-es','se-en','ch-en','tz-en','th-en','tt-en','tn-en','tr-en','ug-en','us-en','vn-en','zm-en','zw-en'];

        foreach($workerEightMapper as $storeCode){
            $data = [
                'worker_name'=>'worker 8',
                'instance_name'=>'',
                'store_code'=>$storeCode,
            ];
            $conn->insert($tableName, $data);
        }

        $setup->endSetup();
    }
}