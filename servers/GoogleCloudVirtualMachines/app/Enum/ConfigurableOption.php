<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Enum;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Enum\Enum;

final  class ConfigurableOption extends Enum
{
    const MACHINE_TYPE ='machineType';
    const IMAGE ='image';
    const CUSTOM_MACHINE_TYPE = 'customMachineType';
    const CUSTOM_MACHINE_CPU = 'customMachineCpu';
    const CUSTOM_MACHINE_RAM = 'customMachineRam';

}