<?php
namespace modules\Register;

class Register extends \modules\User\User {
    public function register() {
        // Erreurs possibles :
        // 1 - Champ non transmis
        // 2 - Champ vide
        // 3 - Champ non valide
        // 4 - Valeur déjà utilisée

        $errors = array();

        // Flood registration sur une adresse IP (1 heure)
        $result = $this->db->query('SELECT 1 FROM '.$this->table.' WHERE registration_ip=\''.$this->db->escape(get_remote_address()).'\' AND registered>'.(time() - 3600)) or error('Impossible de tester la précédente inscription sur cette adresse IP', __FILE__, __LINE__, $this->db->error());

        if ($this->db->num_rows($result))
            $errors['ip'] = 4;

        // On s'assure que tous les champs sont fournis
        if(empty($errors)) {
            $fields = array('email', 'fullname');
            foreach($fields AS $field) {
                if(isset($_POST[$field])) {
                    ${$field} = pun_trim($_POST[$field]);

                    if(empty(${$field}))
                        $errors[$field] = 2;
                }
                else
                    $errors[$field] = 1;
            }
        }

        // On teste la validité de l'adresse email s'il n'y pas d'erreurs
        if(empty($errors)) {
            $email = strtolower($email);

            // On vérifie l'adresse email
            if(!isValidEmail($email))
                $errors['email'] = 3;
            // On vérifie que l'adresse n'est pas déjà utilisée
            else {
                if($this->emailExists($email))
                    $errors['email'] = 4;
            }
        }

        // Génération d'un mot de passe aléatoire
        $password = random_pass(8);
        $password_clean = $password;

        // Si tout va bien on créé le compte
        if(empty($errors)) {
            $datas = array();
            $datas['username'] = $email;
            $datas['password'] = pun_hash($password);
            $datas['email'] = $email;
            $datas['realname']  = $fullname;
            $datas['group_id'] = 0;
            $datas['language'] = 'English';
            $datas['style'] = 'Air';
            $datas['registered'] = time();
            $datas['last_visit'] = time();
            $datas['registration_ip'] = get_remote_address();

            $newUser = new \modules\User\User(true);
            $this->hydrate($datas);

            // Création du nouveau compte
            $id = $this->add();

            // Insertion des lignes de droits dans la table users_right
            $sections = array(
                'biblio' => 'Vidéothèque',
                'towatchlist' => 'Films à voir',
                'lastview' => 'Derniers vus',
                'wishlist' => 'Whishlist',
                'stats' => 'Statistiques');

            foreach($sections AS $key => $name) {
            $this->db->query(\library\Query::Insert('users_right', array('userid' => $id, 'action' => $key, 'guest' => '0', 'membre' => '1', 'friend' => '1'), false, false));
            }

            // On envoie l'email avec le mot de passe
            $this->sendRegister($email, $password_clean, $email);
        }
        return $errors;
    }

    public function sendRegister($username, $password, $email) {
        require PUN_ROOT.'include/email.php';

        // Load the "welcome" template
        $mail_tpl = trim(file_get_contents(ROOT.'assets/mail_template/welcome.tpl'));

        // The first row contains the subject
        $first_crlf = strpos($mail_tpl, "\n");
        $mail_subject = trim(substr($mail_tpl, 8, $first_crlf-8));
        $mail_message = trim(substr($mail_tpl, $first_crlf));

        $mail_subject = str_replace('<board_title>', 'My Movie Wall', $mail_subject);
        $mail_message = str_replace('<base_url>', WWW_ROOT, $mail_message);
        $mail_message = str_replace('<username>', $username, $mail_message);
        $mail_message = str_replace('<password>', $password, $mail_message);
        $mail_message = str_replace('<login_url>', WWW_ROOT.'login', $mail_message);
        $mail_message = str_replace('<board_mailer>', 'My Movie Wall', $mail_message);

        pun_mail($email, $mail_subject, $mail_message);
    }
}