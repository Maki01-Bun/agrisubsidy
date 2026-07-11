<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;

/**
 * Token Controller
 *
 * @method \App\Model\Entity\Token[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TokenController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->request->allowMethod(['get']);
        $csrfToken = $this->request->getAttribute('csrfToken');

        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['csrfToken' => $csrfToken]));
    }
}
