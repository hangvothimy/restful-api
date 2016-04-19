<?php

class Lazada_Core_Template extends Smarty
{
	function __construct($RootPath)
	{
		parent::__construct();
		$ini			=	new Lazada_Core_Ini;
		$template_ini	=	$ini->getIni("template.ini","Config");
		$this->debugging = filter_var($template_ini["Debug"], FILTER_VALIDATE_BOOLEAN);
		$this->force_compile = filter_var($template_ini["Compile"], FILTER_VALIDATE_BOOLEAN);
		$this->caching = filter_var($template_ini["Cache"], FILTER_VALIDATE_BOOLEAN);
		$this->compile_check = filter_var($template_ini["CompileCheck"], FILTER_VALIDATE_BOOLEAN);
		$this->cache_lifetime = filter_var($template_ini["Lifetime"], FILTER_VALIDATE_BOOLEAN);
		$this->template_dir = $RootPath . $template_ini["TemplateDir"];
		$this->compile_dir = $RootPath. $template_ini["CompileDir"];
		$this->cache_dir = $RootPath. $template_ini["CacheDir"];
		
	}
}
?>