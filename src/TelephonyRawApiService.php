<?php

namespace Enj0yer\CrmTelephony;

use Enj0yer\CrmTelephony\Processors\ProcessBot;
use Enj0yer\CrmTelephony\Processors\ProcessBotMapping;
use Enj0yer\CrmTelephony\Processors\ProcessBotOperation;
use Enj0yer\CrmTelephony\Processors\ProcessCalls;
use Enj0yer\CrmTelephony\Processors\ProcessDial;
use Enj0yer\CrmTelephony\Processors\ProcessDialTask;
use Enj0yer\CrmTelephony\Processors\ProcessNodeOperation;
use Enj0yer\CrmTelephony\Processors\ProcessOperators;
use Enj0yer\CrmTelephony\Processors\ProcessPhoneGroups;
use Enj0yer\CrmTelephony\Processors\ProcessPhones;
use Enj0yer\CrmTelephony\Processors\ProcessRecords;
use Enj0yer\CrmTelephony\Processors\ProcessRetryLogic;
use Enj0yer\CrmTelephony\Processors\ProcessSchedule;

class TelephonyRawApiService
{
    public function operators(): ProcessOperators
    {
        return new ProcessOperators("/operator");
    }

    public function calls(): ProcessCalls
    {
        return new ProcessCalls("/originate");
    }

    public function phones(): ProcessPhones
    {
        return new ProcessPhones("/phonegroup/phone");
    }

    public function phoneGroups(): ProcessPhoneGroups
    {
        return new ProcessPhoneGroups("/phonegroup");
    }

    public function schedule(): ProcessSchedule
    {
        return new ProcessSchedule("/schedule");
    }

    public function records(): ProcessRecords
    {
        return new ProcessRecords("/");
    }

    public function retryLogic() : ProcessRetryLogic
    {
        return new ProcessRetryLogic("/retry");
    }

    public function dialTasks(): ProcessDialTask
    {
        return new ProcessDialTask("/dialtask");
    }

    public function dialProcess(): ProcessDial
    {
        return new ProcessDial("/dialprocess");
    }

    public function botMapping(): ProcessBotMapping
    {
        return new ProcessBotMapping("/bot/mapping");
    }

    public function botOperation(): ProcessBotOperation
    {
        return new ProcessBotOperation("/bot/dtmf_bot");
    }

    public function botNodeOperations(): ProcessNodeOperation
    {
        return new ProcessNodeOperation("/bot/input/dtmf_node");
    }

    public function bot(): ProcessBot
    {
        return new ProcessBot("/bot");
    }
}