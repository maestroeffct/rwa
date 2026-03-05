<?php


function getDesign1StylePath($name, $folder = "parts"): string
{
    return "/assets/design_1/css/{$folder}/{$name}.min.css";
}

function getDesign1ScriptPath($name, $folder = "parts"): string
{
    return "/assets/design_1/js/{$folder}/{$name}.min.js";
}

function getLandingComponentStylePath($name): string
{
    return "/assets/design_1/landing_builder/components/{$name}.min.css";
}

function getLandingComponentScriptPath($name): string
{
    return "/assets/design_1/landing_builder/js/components/{$name}.min.js";
}
