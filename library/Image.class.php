<?php
namespace library;

class Image {
    
    /**
     * Image::copyIndex()
     *
     * @param string $folder
     * @return void
     */
    private function copyIndex($folder){
        if(!file_exists($folder.'/index.html'))
            copy('img/index.html', $folder.'/index.html');  
    }

    /**
     * Image::folder()
     *
     * @param int $ficheid
     * @return void
     */
    private function folder($ficheid){
        return intval($ficheid / 100);
    }

    /**
     * Image::createFolder()
     *
     * @param string $fichetype
     * @param int $ficheid
     * @return void
     */
    public function createFolder($fichetype, $ficheid) {        
        // Création du dossier parent
        $folder = intval($ficheid / 100);
        
        // On regarde si le dossier de la fiche existe
        if(!file_exists(FOLDER_IMAGES.'/'.$fichetype.'/'.$folder.'/index.html')) {
            // Création du dossier de la fiche, avec les parents, s'il n'existe pas encore
            mkdir(FOLDER_IMAGES.'/'.$fichetype.'/'.$folder, 0777, true);

            // Ajout des fichiers index.htm
            $this->copyIndex(FOLDER_IMAGES.'/'.$fichetype);
            $this->copyIndex(FOLDER_IMAGES.'/'.$fichetype.'/'.$folder);
        }
    }

    /**
     * Image::download()
     *
     * @param string $url
     * @param string $fichetype
     * @param int $ficheid
     * @return void
     */
    public function download($url, $fichetype, $ficheid) {      
        if($url != '') {
            $ficheid = intval($ficheid);

            // Création des dossiers
            $this->createFolder($fichetype, $ficheid);
            
            if(file_exists(FOLDER_IMAGES.'/temp/'.$ficheid.'.jpg'))
                unlink(FOLDER_IMAGES.'/temp/'.$ficheid.'.jpg');

            if(file_exists(FOLDER_IMAGES.'/'.$fichetype.'/'.$this->folder($ficheid).'/'.$ficheid.'.jpg'))
                unlink(FOLDER_IMAGES.'/'.$fichetype.'/'.$this->folder($ficheid).'/'.$ficheid.'.jpg');
            
            // On vérifie l'extension
            if(substr($url, -3) == 'jpg' OR substr($url, -4) == 'jpeg') {
            // Téléchargement de l'image dans le dossier temp
            copy($url, FOLDER_IMAGES.'/temp/'.$ficheid.'.jpg');

            // On redimensionne l'image pour qu'elle respecte les bonnes proportions
            $this->imageResize(FOLDER_IMAGES.'/temp/'.$ficheid.'.jpg', FOLDER_IMAGES.'/'.$fichetype.'/'.$this->folder($ficheid), $ficheid, 350, 480);
            }
        }
    }

     /**
      * Image::imageResize()
      * 
      * @param string $source
      * @param string $folder_destination
      * @param string $nom
      * @param int $largeur
      * @param int $hauteur
      * @return void
      */
     public function imageResize($url, $folder, $nom, $largeur, $hauteur) {
        // if(filesize($url) < 1100000) {
            $source = imagecreatefromjpeg($url); // La photo est la source
            $largeur_source = imagesx($source);
            $hauteur_source = imagesy($source);

            $image = imagecreatetruecolor($largeur, $hauteur);
            imagecopyresampled($image, $source, 0, 0, 0, 0, $largeur, $hauteur, $largeur_source, $hauteur_source);
            
            imagedestroy($source);

            // On supprime l'image de destination si elle existe
            if(file_exists($folder.'/'.$nom.'.jpg'))
                unlink($folder.'/'.$nom.'.jpg');

            // On enregistre l'image redimensionnée
            imagejpeg($image, $folder.'/'.$nom.'.jpg', 100);

            // On supprime l'image source
            if(file_exists($url))
                unlink($url);

            imagedestroy($image);

            // On vérifie la taille de l'image de sortie
            if(filesize($folder.'/'.$nom.'.jpg') < 4000)
                unlink($folder.'/'.$nom.'.jpg');
        // }
    }

    public static function getUrl($type, $id) {
        $url = 'img/'.$type.'/'.intval($id / 100).'/'.$id.'.jpg';
        if(file_exists($url))
            return $url;
        else
            return 'img/empty.jpg';
    }

    public static function generateAffiche($url, $titre) {
        if(!file_exists($url)) {
            $image = imagecreatefromjpeg('img/empty.jpg');
            imagestring($image, 5, 10, 360, $titre, imagecolorallocate($image, 255, 255, 255));
            imagejpeg($image, $url);
        }
    }
}