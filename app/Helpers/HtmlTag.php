<?php

function assets_css_public(string $filename,bool $echo=true)
{
    $fileAssets = 'assets/custom/public/css/'.$filename.'.css';
    if (file_exists($fileAssets)) {
        $result = asset('').$fileAssets.'?vfm='.filemtime($fileAssets);
        $linkTag = '<link rel="stylesheet" href="'.$result.'"></link>';
        if ($echo) {
            echo $linkTag;
        } else {
            return $linkTag;
        }
    } else {
        echo "File css ".asset($fileAssets)." not found..!!";
    }
}

function assets_js_public(string $filename,bool $echo=true)
{
    $fileAssets = 'assets/custom/public/js/'.$filename.'.js';
    if (file_exists($fileAssets)) {
        $result = asset('').$fileAssets.'?vfm='.filemtime($fileAssets);
        $linkTag = '<script type="text/javascript" src="'.$result.'"></script>';
        if ($echo) {
            echo $linkTag;
        } else {
            return $linkTag;
        }
    } else {
        echo "File Javascript ".asset($fileAssets)." not found..!!";
    }
}

function assets_css_back(string $filename,bool $echo=true)
{
    $fileAssets = 'assets/custom/back/css/'.$filename.'.css';
    if (file_exists($fileAssets)) {
        $result = asset('').$fileAssets.'?vfm='.filemtime($fileAssets);
        $linkTag = '<link rel="stylesheet" href="'.$result.'"></link>';
        if ($echo) {
            echo $linkTag;
        } else {
            return $linkTag;
        }
    } else {
        echo "File css ".asset($fileAssets)." not found..!!";
    }
}

function assets_js_back(string $filename,bool $echo=true)
{
    $fileAssets = 'assets/custom/back/js/'.$filename.'.js';
    if (file_exists($fileAssets)) {
        $result = asset('').$fileAssets.'?vfm='.filemtime($fileAssets);
        $linkTag = '<script type="text/javascript" src="'.$result.'"></script>';
        if ($echo) {
            echo $linkTag;
        } else {
            return $linkTag;
        }
    } else {
        echo "File Javascript ".asset($fileAssets)." not found..!!";
    }
}

function assetImg_thumbnail()
{
    return asset('assets/custom/img/img_thumbnail.png');
}

function alertSuccess(string $message): string
{
    return '<div class="alert alert-success text-center"><b>'.$message.'</b></div><br>';
}

function alertInfo(string $message): string
{
    return '<div class="alert alert-info text-center"><b>'.$message.'</b></div><br>';
}

function alertWarning(string $message): string
{
    return '<div class="alert alert-warning text-center"><b>'.$message.'</b></div><br>';
}

function alertDanger(string $message): string
{
    return '<div class="alert alert-danger text-center"><b>'.$message.'</b></div><br>';
}

function spanRed(string $message): string
{
    return '<span style="color:red;">'.$message.'</span>';
}

function spanGreen(string $message): string
{
    return '<span style="color:green;">'.$message.'</span>';
}

function spanBlue(string $message): string
{
    return '<span style="color:blue;">'.$message.'</span>';
}

function spanOrange(string $message): string
{
    return '<span style="color:orange;">'.$message.'</span>';
}

function spanGray(string $message): string
{
    return '<span style="color:grey;">'.$message.'</span>';
}

function tagSmall(string $message): string
{
    return '<small style="font-size:12px;">'.$message.'</small>';
}

function requestIsActive(string $url): string
{
    return request()->is($url) ? 'active':'';
}

function requestGroupActive(array $arrUrl): bool
{
    if(in_array(request()->path(),$arrUrl)){
        return true;
    } else {
        return false;
    }
}