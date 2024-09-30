<?php

namespace Enj0yer\CrmTelephony\Helpers;

class BotInputDtmfStepSchema
{
    private array $actions = [
        "action_for_0" => null,
        "action_for_1" => null,
        "action_for_2" => null,
        "action_for_3" => null,
        "action_for_4" => null,
        "action_for_5" => null,
        "action_for_6" => null,
        "action_for_7" => null,
        "action_for_8" => null,
        "action_for_9" => null,
        "action_for_star" => null,
        "action_for_hash" => null,
        "action_for_i" => null,
        "action_for_t" => null
    ];

    public function getActions(): array
    {
        return $this->actions;
    }

    public function actionFor0(string | null $action): self
    {
        $this->actions['action_for_0'] = $action;
        return $this;
    }

    public function actionFor1(string | null $action): self
    {
        $this->actions['action_for_1'] = $action;
        return $this;
    }

    public function actionFor2(string | null $action): self
    {
        $this->actions['action_for_2'] = $action;
        return $this;
    }

    public function actionFor3(string | null $action): self
    {
        $this->actions['action_for_3'] = $action;
        return $this;
    }

    public function actionFor4(string | null $action): self
    {
        $this->actions['action_for_4'] = $action;
        return $this;
    }

    public function actionFor5(string | null $action): self
    {
        $this->actions['action_for_5'] = $action;
        return $this;
    }

    public function actionFor6(string | null $action): self
    {
        $this->actions['action_for_6'] = $action;
        return $this;
    }

    public function actionFor7(string | null $action): self
    {
        $this->actions['action_for_7'] = $action;
        return $this;
    }

    public function actionFor8(string | null $action): self
    {
        $this->actions['action_for_8'] = $action;
        return $this;
    }

    public function actionFor9(string | null $action): self
    {
        $this->actions['action_for_9'] = $action;
        return $this;
    }

    public function actionForStar(string | null $action): self
    {
        $this->actions['action_for_star'] = $action;
        return $this;
    }

    public function actionForHash(string | null $action): self
    {
        $this->actions['action_for_hash'] = $action;
        return $this;
    }

    public function actionForI(string | null $action): self
    {
        $this->actions["action_for_i"] = $action;
        return $this;
    }

    public function actionForT(string | null $action): self
    {
        $this->actions["action_for_t"] = $action;
        return $this;
    }
}