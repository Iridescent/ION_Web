<?php

class SynchronizationController extends Controller {
    public function actionSynchronizeWithCuriosityMachine() {
        $worker = SynchronizationWorker::Instance();
        $worker->Start();
    }
}

?>
