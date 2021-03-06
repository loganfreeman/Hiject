<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Hiject\Formatter\TaskAutoCompleteFormatter;
use Hiject\Model\ProjectModel;
use Hiject\Model\TaskCreationModel;
use Hiject\Model\TaskFinderModel;

require_once __DIR__.'/../Base.php';

class TaskAutoCompleteFormatterTest extends Base
{
    public function testFormat()
    {
        $projectModel = new ProjectModel($this->container);
        $taskCreationModel = new TaskCreationModel($this->container);
        $taskFinderModel = new TaskFinderModel($this->container);

        $this->assertEquals(1, $projectModel->create(array('name' => 'My Project')));
        $this->assertEquals(1, $taskCreationModel->create(array('title' => 'Task 1', 'project_id' => 1)));
        $this->assertEquals(2, $taskCreationModel->create(array('title' => 'Task 2', 'project_id' => 1)));

        $tasks = TaskAutoCompleteFormatter::getInstance($this->container)
            ->withQuery($taskFinderModel->getExtendedQuery())
            ->format();

        $this->assertCount(2, $tasks);
        $this->assertEquals('My Project > #1 Task 1', $tasks[0]['label']);
        $this->assertEquals('Task 1', $tasks[0]['value']);
        $this->assertEquals(1, $tasks[0]['id']);
        $this->assertEquals('My Project > #2 Task 2', $tasks[1]['label']);
        $this->assertEquals('Task 2', $tasks[1]['value']);
        $this->assertEquals(2, $tasks[1]['id']);
    }
}
