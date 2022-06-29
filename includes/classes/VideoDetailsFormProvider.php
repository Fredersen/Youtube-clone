<?php

class VideoDetailsFormProvider {

    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function createUploadForm() {
        $fileInput = $this->createFileInput();
        $titleInput = $this->createTitleInput();
        $descriptionInput = $this->createDescriptionInput();
        $privacyInput = $this->createPrivacyInput();
        $categoriesInput = $this->createCategoriesInput();
        $uploadButton = $this->createUploadButton();

        return "<form action='processing.php' method='POST' enctype='multipart/form-data'>
                    $fileInput
                    $titleInput
                    $descriptionInput
                    $privacyInput
                    $categoriesInput
                    $uploadButton
                </form>";
    }

    public function createEditDetailsForm($video) {
        $titleInput = $this->createTitleInput($video->getTitle());
        $descriptionInput = $this->createDescriptionInput($video->getDescription());
        $privacyInput = $this->createPrivacyInput($video->getPrivacy());
        $categoriesInput = $this->createCategoriesInput($video->getCategory());
        $saveButton = $this->createSaveButton();
        return "<form method='POST'>
                    $titleInput
                    $descriptionInput
                    $privacyInput
                    $categoriesInput
                    <div class='form-group'>
                        $saveButton
                    </div>
                </form>";
    }

    private function createFileInput() {
        return "<div class='form-group'>
                    <input type='file' class='form-control-file' id='exampleFormControlFile1' name='fileInput' required>
                </div>";
    }

    private function createTitleInput($value = null) {
        if($value == null) $value='';
        return "<div class='form-group'>
                    <input class='form-control' type='text' placeholder='Title' name='titleInput' value='$value'>
                </div>";
    }

    private function createDescriptionInput($value = null) {
        if($value == null) $value='';
        return "<div class='form-group'>
                    <textarea class='form-control' placeholder='Description' name='descriptionInput' rows='3'>$value</textarea>
                </div>";
    }

    private function createPrivacyInput($value = null) {
        if($value == null) $value='';

        $privateSelected = ($value == 0) ? "selected='selected'" : "";
        $publicSelected = ($value == 1) ? "selected='selected'" : "";
        return "<div class='form-group'>
                    <select class='form-control' name='privacyInput'>
                        <option value='0' $privateSelected>Private</option>
                        <option value='1' $publicSelected>Public</option>
                    </select>
                 </div>";
    }

    private function createCategoriesInput($value = null) {
        if($value == null) $value='';
        $query = $this->con->query("SELECT * FROM categories");
        $categories = $query->fetchAll();

        $html = "<div class='form-group'>
        <select class='form-control' name='categoryInput'>";

        foreach($categories as $key => $category) {
            $selected = ($category['id'] == $value) ? "selected='selected'" : "";
            $html .= "<option $selected value=" . $category['id'] . ">" . $category['name'] . "</option>";
        }

        $html .= " </select></div>";

        return $html;
    }

    private function createUploadButton() {
        return "<button type='submit' name='uploadButton' class='btn btn-primary'>Upload</button>";
    }

    private function createSaveButton() {
        return "<button type='submit' name='saveButton' class='btn btn-primary'>Save</button>";
    }
}

?>