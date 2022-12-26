<?php


namespace ModulesGarden\Servers\AwsEc2\Packages\Provisioning\Database;

use ModulesGarden\Servers\AwsEc2\App\Models\AvailableImages\Model;
use ModulesGarden\Servers\AwsEc2\App\Models\AvailableImages\Repository;
use ModulesGarden\Servers\AwsEc2\Core\Models\Whmcs\Product;
use ModulesGarden\Servers\AwsEc2\Core\UI\Traits\WhmcsParams;
use \Illuminate\Database\Capsule\Manager as DB;

class Update implements Updatable
{
    use WhmcsParams;
    public function update()
    {
        $model = new Model();
        $schemaBuilder = $model->getConnection()->getSchemaBuilder();
        if(!($schemaBuilder->hasColumn($model->getTable(), 'name') && $schemaBuilder->hasColumn($model->getTable(), 'region'))) {
            return false;
        }
        $this->updateUniqueConstraints($schemaBuilder);
        return true;
    }
    
    public function updateUniqueConstraints($schemaBuilder)
    {
        $model = new Model();
        $keys = DB::select(DB::raw('SHOW KEYS from ' . $model->getTable()));
        $shouldDropUnique = true;
        $keyExists = false;
        $nameColumnDataType = DB::select(DB::raw("SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . $model->getTable() . "' AND COLUMN_NAME = 'name'"))[0];
        foreach ($keys as $key) {
            if($key->Column_name === 'product_id' && !$key->Non_unique && $key->Key_name === 'image_name_region')
            {
                $shouldDropUnique = false;
            }
            if($key->Key_name === 'image_name_region')
            {
                $keyExists = true;
            }
        }

        if($shouldDropUnique) {
            $schemaBuilder->table($model->getTable(), function (\Illuminate\Database\Schema\Blueprint $table) use ($keyExists, $nameColumnDataType)
            {
                if($keyExists)
                {
                    $table->dropUnique('image_name_region');
                }
                if($nameColumnDataType->DATA_TYPE === 'text')
                {
                    DB::select(DB::raw('ALTER TABLE ' . $table->getTable() . ' MODIFY name varchar(128) NOT NULL, MODIFY region varchar(128) NOT NULL'));
                }
                $table->unique(['name', 'region', 'product_id'], 'image_name_region');
            });
        }
    }

    public function prepareForConstraints()
    {
        return $this->prepareRecordsForUniqueKey();
    }

    private function prepareRecordsForUniqueKey()
    {
        if (!$this->update())
            return false;


        $product = new Product();
        $productIds = $product->select('id')->where('servertype', 'AwsEc2')->get();
        if(count($productIds) === 0)
            return false;


        foreach ($productIds as $id) {
            $pid = $id['id'];
            $repo = new Repository();
            $amis = $repo->getAmisForProduct($pid);

            foreach ($amis as $ami) {
                $productSettings = new \ModulesGarden\Servers\AwsEc2\Core\Models\ProductSettings\Repository();
                $region = $productSettings->getProductSettings($pid)['region'];

                $modelInstance = new Model();
                $instance = $modelInstance->where('product_id', $pid)->where('image_id', $ami['image_id'])->first();
                if($instance->region || $instance->name)
                    return false;
                $instance->region = $region;
                $instance->name = $ami['details']['Name'];
                $instance->save();
            }
        }
        return true;
    }
}
