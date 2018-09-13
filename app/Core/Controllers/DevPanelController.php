<?php

namespace Core\Controllers;

use Core\Router\Request;
use Core\Controllers\BaseController;
use Core\Console\MakeControllerCommand;
use Core\Console\MakeModelCommand;


class DevPanelController extends BaseController
{


  public function index()
  {
    $this->render('dev-panel');
  }


  /**
   * Creates a controller.
   */

  public function controller(Request $request)
  {
    $success = MakeControllerCommand::execute(
      $request->body->name, $request->body->resource
    );
    if($success) {
      $this->redirect('/dev-panel', ['success' => ['Controller created successfuly']]);
    } else {
      $this->redirect('/dev-panel', ['errors' => ['Whoops, something went wrong']]);
    }
  }


  public function model(Request $request)
  {
    $success = MakeModelCommand::execute(
      $request->body->name, $request->body->create_table
    );
    if($success) {
      $this->redirect('/dev-panel', ['success' => ['Model created successfuly']]);
    } else {
      $this->redirect('/dev-panel', ['errors' => ['Whoops, something went wrong']]);
    }
  }
}