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
         $user = $this->request->getAttribute('identity');

        $this->set([
            'user' => $user,
            '_serialize' => ['user']
        ]);
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
