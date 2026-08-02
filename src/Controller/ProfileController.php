<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Profile Controller
 *
 * @method \App\Model\Entity\Profile[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProfileController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->loadModel('Users');
        $this->loadModel('Farmers');
        $this->loadModel('Farms');
            $userId = $this->request->getSession()->read('Auth.User.id');
            $user = $this->Users->get($userId);
            $farmer = null;
            $farms = [];
            if (strtolower($user->role) === 'farmer') {
                $farmer = $this->Farmers->find()->where(['user_id' => $user->id])->first();
                if ($farmer) {
                    $farms = $this->Farms->find()->where(['farmer_id' => $farmer->id])->toArray();
                }
            }
            $this->set(compact('user', 'farmer', 'farms'));
    }
     public function edit()
    {
        $user = $this->request->getAttribute('identity');


        if ($this->request->is(['patch','post','put'])) {


            $data = $this->request->getData();


            // Upload Profile Image
            $image = $this->request->getData('profile_image');


            if ($image && $image->getError() === UPLOAD_ERR_OK) {


                $filename = time() . '_' . $image->getClientFilename();


                $folder = WWW_ROOT . 'img' . DS . 'profiles';


                if (!is_dir($folder)) {

                    mkdir(
                        $folder,
                        0777,
                        true
                    );

                }


                $image->moveTo(
                    $folder . DS . $filename
                );


                $data['profile_image'] = $filename;

            }



            $users = $this->fetchTable('Users');


            $user = $users->patchEntity(
                $user,
                $data
            );


            if ($users->save($user)) {


                $this->Flash->success(
                    'Profile updated successfully.'
                );


                return $this->redirect([
                    'action'=>'index'
                ]);

            }


            $this->Flash->error(
                'Profile update failed.'
            );

        }


        $this->set(compact('user'));

    }
}
