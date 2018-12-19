<?php
/**
 * Created by PhpStorm.
 * User: Remi
 * Date: 28/10/2018
 * Time: 19:23
 */

class notification
{

    private $text;
    private $type;
    private $id;

    /**
     * notification constructor.
     */
    public function __construct($text, $type)
    {
        if (!type::isValidName($type)){
            throw new Exception( "Erreur dans le type de notifications!" );
        }
        $this->text = addslashes($text);
        $this->type = $type;
        $this->id = uniqid();
        $this->afficherNotification();
    }



    public function afficherNotification(){

        ?>
        <script type='text/javascript'>
            var id="<?php echo $this->id ?>";
            document.getElementById('bloc-de-notifications').style.display = 'block';
            document.getElementById('bloc-de-notifications').innerHTML += '<div id="'+id+'" class=\'notice <?php echo $this->type ?>\'><?php echo $this->text ?></div>';
            var compte = ($('.notice').length-1)*75+"px";

            $('#'+id).css("margin-top",compte);
            // alert(top);


        </script>
<?php


    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }









}