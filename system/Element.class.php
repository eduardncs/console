<?php
namespace Rosance
{
    /**
     * This class is used for serverSide DOM manipulation
     * Copyright (C) 2020-2021 by Eduard Neacsu
     * Created for Rosance, https://rosance.com
     */
    use Rosance\Callback;
    use Rosance\User;
    use phpQuery;
    require_once("Callback.class.php");
    require_once("phpQuery.php");
    class Element
    {
        const DEFAULT = "this";
        private $document;
        private $path;
        public $element;

        /**
         * Create a php DOM document and find a spcified element
         * At the very base of this script stands phpQuery
         * All opreations are done within class Element
         * @param User $user 
         * @param Project $project 
         * @param string $id 
         * @param string $page 
         */
        function __construct(User $user, Project $project, $id , $page)
        {
            $this->path = "../clients/".$user->Business_Name."/".$project->project_name_short."/".lcfirst($page).".php";
            $content = file_get_contents($this->path);
            $this->document = phpQuery::newDocumentPHP($content);
            $this->element = pq($this->document)->find(".".$id);
        }

        /**
         * @return Callback $callback 
         */
        private function save(){

            $contentEdited =  self::trimPHP(htmlspecialchars_decode($this->document->html()));
            $callback = new Callback;
            file_put_contents($this->path,$contentEdited);
            return $callback->SendSuccessToast("Success!");
        }

        /**
         * Change the text of a dom document
         * Will probably be revisited in further versions , for now it does the job well
         * @param string $text 
         */
        public function changeText($text){
            $this->element->text($text);
            $this->save();
        }
        /**
         * Array key should be the style name and array value should be the style value
         * Ex: ["background-color"=>"#fff"]
         * @param array $array_of_styles 
         */
        public function changeStyles($array_of_styles){
            var_dump($array_of_styles);
            $newcss = $this->element->attr("style");
            $keys = array_keys($array_of_styles);
            for($i=0; $i < count($keys);$i++)
            {
                $newcss = $this->decodeCSS($newcss,trim($keys[$i]), $array_of_styles[$keys[$i]]);
            }
            $this->element->attr('style',$newcss);
            $this->save();
        }
        /**
         * Change attribute of a dom element
         * !!! For multiple attributes changes use changeattributes(array) function instead
         * @param string $attribute
         * @param string $value
         */
        public function changeAttribute($attribute,$value){
            $this->element->attr($attribute,$value);
            $this->save();
        }
        /**
         * Change atributes of a dom element
         * !!! For single atrribute use changeattribute($attrivute,$value) function instead
         * Array key should be the attribute name and array value should be the attribute value
         * Ex: ["title"=>"foo"]
         * @param array $array_of_atributes 
         */
        public function changeAttributes($array_of_atributes){
            $keys = array_keys($array_of_atributes);
            foreach($keys as $key)
            {
                $this->element->attr($key,$array_of_atributes[$key]);
            }
            $this->save();
        }
        /**
         * Add class to a dom element if not exists allready
         * Can run a function as second argument that is run before class is added
         * @param mixed $classes Either a string with class name or an array of classes
         * @param array $actionBefore [string $functionName, array $args] Null by default
         */
        public function addClasses($classes,$actionBefore = null){
            if($actionBefore != null)
                call_user_func_array(array($this,$actionBefore[0]),$actionBefore[1]);
            
            if(!is_array($classes))
            {
                if(!$this->element->hasClass($classes))
                {
                    $this->element->addClass($classes);
                }
            }
            else
            {
                for ($i=0; $i < count($classes); $i++) { 
                    if(empty($classes[$i]) or $classes[$i] = '')
                        return;
                    if(!$this->element->hasClass($classes[$i]))
                        $this->element->addClass($classes[$i]);
                }
            }
            $this->save();
        }
        /**
         * Remove class of a dom element 
         * Either a string with class name or an array of classes
         * @param mixed $classes
         */
        public function removeClasses($classes,$save = true){
            if(!is_array($classes))
            {
                $this->element->removeClass($classes);
            }
            else
            {
                for ($i=0; $i < count($classes); $i++) { 
                    $this->element->removeClass($classes[$i]);
                }
            }
            if($save)
                $this->save();
        }
        /**
         * Append an element after this element on curent dom order
         * @param string $elementstringified
         * @param bool $save By default is set to true
         */
        public function addAfter($elementstringified,$save = true){
            $this->element->after($elementstringified);
            if($save)
                $this->save();
        }
        /**
         * Append an element before this element on curent dom order
         * @param string $elementstringified
         * @param bool $save By default is set to true
         */
        public function addBefore($elementstringified, $save = true){
            $this->element->before($elementstringified);
            if($save)
                $this->save();
        }
        /**
         * Add a chield at the end of the element
         * @param string $elementstringified
         */
        public function addChield($elementstringified,$save = true){
            $this->element->append($elementstringified);
            if($save)
                $this->save();
        }
        /**
         * Append an element inside this element at the end
         * @param string $elementstringified
         * @param bool $save By default is set to true
         * @param mixed $where Selector chield of this element , don't forget . or # 
         */
        public function append($elementstringified, $save = true, $where = self::DEFAULT){
            $where != self::DEFAULT ? $append = pq($this->element)->find($where) : $append = $this->element;
            pq($append)->append($elementstringified);
            if($save)
                $this->save();
        }
        /**
         * Prepend an element inside this element at the start
         * @param string $elementstringified
         * @param bool $save By default is set to true
         */
        public function prepend($elementstringified, $save = true){
            $this->element->prepend($elementstringified);
            if($save)
                $this->save();
        }
        /**
         * Remove this element from DOM
         * @param bool $save By default is set to true
         */
        public function remove($save = true){
            $this->element->remove();
            if($save)
                $this->save();
        }
        /**
         * Remove an element from DOM \n
         * Element MUST be a chield of the DOM element
         * @param string $elementid Must be a selector, don't forget . or #
         * @param bool $save By default is set to true
         */
        public function removeSpecified($elementid,$save = true){
            $element = $this->element->find($elementid);
            $element->remove();
            if($save)
                $this->save();
        }
        /**
        * Update columns of section
        * Find the row inside dom element
        * And update all columns with a specific col- class
        * A function can be called as second argument that will run before updateColumns
        * @param string $rowClass
        * @param string $columnClass
        * @param array $actionBefore [string $functionName, array $args] Null by default
        */
        public function updateColumns($rowClass,$columnClass,$actionBefore = null){
            if($actionBefore != null)
                call_user_func_array(array($this,$actionBefore[0]),$actionBefore[1]);
            //Find the row container
            if($columnClass === 0)
                return;
            $row = pq($this->element)->find(".".$rowClass);
            $columns = $row->find("div[class^='col-md-'],div[class*=' col-md-']");
            foreach($columns as $col){
                $col = pq($col);
                $classes = explode(" ",$col->attr("class"));
                for($i=0;$i<count($classes);$i++){
                    if(strpos($classes[$i] , "col-md-")!== false){
                        //Contains 
                        $classes[$i] = $columnClass;
                    }
                }
                $classes = implode(" ",$classes);
                $col->attr("class",$classes);
            }
            $this->save();
        }
        /**
         * Move this element after that element
         * @param string $that
         */
        public function moveAfter($that){
            $that = pq($this->document)->find(".".$that);
            pq($that)->after($this->element);
            $this->save();
        }
        /**
         * Move this element before that element
         * @param string $that
         */
        public function moveBefore($that){
            $that = pq($this->document)->find(".".$that);
            pq($that)->before($this->element);
            $this->save();
        }
        /**
         * Set an attribute of the element
         * @param string $attr
         * @param string $value
         */
        public function setAttr($attr,$value){
            $this->element->attr($attr,$value);
            $this->save();
        }
        /**
         * Loops trought all css of a dom element and change only one value
         * If value is not found , it is appended to the css
         * @param string $css
         * @param string $valuetolookfor
         * @param string valuetoreplace
         * @param string $alternative
         * @return string $css
         */
        private function decodeCSS($css,$valuetolookfor, $valuetoreplace , $alternative=null)
        {
            $css = explode(";",$css);
            $found = false;
            for($i=0; $i < count($css); $i++)
            {
                $found = false;
                if($i == count($css)-1)
                    break;
                $p_scss = explode(":",$css[$i]);
                if(trim($p_scss[0]) == $valuetolookfor)
                {
                    $css[$i] = trim($p_scss[0]).":".$valuetoreplace;
                    $found = true;
                break;
                }elseif($alternative != null){
                    if (trim($p_scss[0]) == $alternative)
                    {
                        $css[$i] = trim($p_scss[0]).":".$valuetoreplace;
                        $found = true;
                    break;
                    }
                }
            }
            $css = implode(";",$css);
            if(!$found)
                $css .= $valuetolookfor.":".$valuetoreplace.";";
            return $css;
        }
        /**
         * Code coming from phpquery->html()
         * It's a workaround to phpQuery bug where it adds whitespaces to php code used into attributes
         * @param string $code 
         */
        private function trimPHP($code)
        {
            $result = $code;
            $result = str_ireplace("<php><!--","<?php",$result);
            $result = str_ireplace("--></php>","?>",$result);
            $result = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $result);
            return trim($result);
        }
    }
}

?>