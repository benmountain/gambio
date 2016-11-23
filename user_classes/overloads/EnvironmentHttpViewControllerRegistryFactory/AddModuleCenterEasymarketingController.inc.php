<?php
/* -----------------------------------------------------------------------------------------
   Easymarketing Modul

   Copyright (c) 2016 [www.easymarketing.de]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License (Version 2)
   [http://www.gnu.org/licenses/gpl-2.0.html]
   -----------------------------------------------------------------------------------------
   
   @author		Florian Ressel <florian.ressel@easymarketing.de>

   @file       user_classes/overloads/EnvironmentHttpViewControllerRegistryFactory/AddModuleCenterEasymarketingController.inc.php
   @version    v3.0.1
   @updated    23.11.2016 - 13:37
   ---------------------------------------------------------------------------------------*/

class AddModuleCenterEasymarketingController extends AddModuleCenterEasymarketingController_parent
{
    /**
     * Adds new available controller to the registry.
     *
     * @param HttpViewControllerRegistryInterface $registry Registry object which adds the new controller entries.
     */
    protected function _addAvailableControllers(HttpViewControllerRegistryInterface $registry)
    {
        parent::_addAvailableControllers($registry);

        $registry->set('EasymarketingModuleCenterModule', 'EasymarketingModuleCenterModuleController');
    }
}

?>