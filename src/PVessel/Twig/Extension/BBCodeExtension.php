<?php
 
namespace PVessel\Twig\Extension;
 
class BBCodeExtension extends \Twig_Extension
{
    private $search  = array('[b]', '[/b]', '[i]', '[/i]', '[u]', '[/u]', '[s]', '[/s]', '[code]', '[/code]', '[quote]', '[/quote]', '[/url]' );
    private $replace = array('<b>', '</b>', '<em>', '</em>', '<u>', '</u>', '<del>', '</del>', '<pre>', '</pre>', '<blockquote>', '</              blockquote>', '</a>' );
 
    private $searchRegex  = array('/(\[url=)([^\]]+)(\])/', '/(\[url\])([^\]]+)(\])/', '/(\[img\])([^\[\/img\]]+)(\[\/img\])/' );
    private $replaceRegex = array('<a href="\2">', '<a href="\2">\2', '<img src="\2" alt="" />' );
 
    public function getFilters()
    {
         return array(
            new \Twig_SimpleFilter('bbCode', array($this, 'bbCodeFilter'), array('is_safe' => array('html'))),
            );
    }
 
     /**
      * Converts BBCode tag into HTML tags
      *
      * @param $string String source
      *
      * @return string
      */
    public function bbCodeFilter()
    {
        $arguments = func_get_args();
        $string = array_shift($arguments);

        // Parse any additional parameters to alter tha behaviour of this extension
        $this->parseArguments($arguments);

        return preg_replace($this->getSearchRegex(), $this->getReplaceRegex(), str_replace($this->getSearch(), $this->getReplace(), $string));
    }

    private function parseArguments($arguments)
    {
        foreach ($arguments as $argument) {
            if (is_string($argument)) {
                switch ($argument) {
                    case 'nofollow':
                        $this->replaceRegex[0] = '<a rel="nofollow" href="\2">';
                        $this->replaceRegex[1] = '<a rel="nofollow" href="\2">\2';
                        break;
                    // Can add more cases to add more functionality.
                    default:
                        break;
                }
            }
        }
    }

    // Getter functions

    private function getSearch()
    {
        return $this->search;
    }

    private function getReplace()
    {
        return $this->replace;
    }
 
    private function getSearchRegex()
    {
        return $this->searchRegex;
    }

    private function getReplaceRegex()
    {
        return $this->replaceRegex;
    }

    public function getName()
    {
        return 'bbcode_extension';
    }
}
