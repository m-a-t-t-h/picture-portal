<?php

return [
    // ---- The top level collection ID, as defined in AlbumRoots
    //
    "ROOT_COLLECTION_ID"              => env("DKW_ROOT_COLLECTION_ID"),

    // ---- JSON encoded array of tag IDs to show at the root level of the tree
    //
    "ROOT_TAG_ARRAY"                  => env("DKW_ROOT_TAG_ARRAY"),

    // ---- Whether to require images to be served to have the "Public" tag,
    //      otherwise an image will be considered private
    //
    "REQUIRE_PUBLIC_TAG"              => env("DKW_REQUIRE_PUBLIC_TAG", FALSE),

    // ---- The ID value of the "Public" tag
    //
    "PUBLIC_TAG_ID"                   => env("DKW_PUBLIC_TAG_ID"),

    // ---- How many images to pre-load in each batch
    //
    "INFINITE_SCROLL_PAGE_SIZE"       => env("DKW_INFINITE_SCROLL_PAGE_SIZE"),

    // ---- Where to put the sentinel image to trigger the next batch loading
    //
    "INFINITE_SCROLL_SENTINEL_OFFSET" => env("DKW_INFINITE_SCROLL_SENTINEL_OFFSET"),

    "PAGE_SIZE"                => env("DKW_INFINITE_SCROLL_PAGE_SIZE"),

    // ---- The DigiKam tag that corresponds to the "OnThisDay" label
    //
    "ON_THIS_DAY_TAG_ID"       => env("DKW_ON_THIS_DAY_TAG_ID"),

    // ---- If the mountpoint requires a subdirectory to the image source,
    //      specify it here
    //
    "IMAGE_URL_PREFIX"         => env("DKW_IMAGE_URL_PREFIX"),

    // ---- If the mountpoint requires a leading part of the image path to  be removed,
    //      specify it here
    //
    "IMAGE_URL_PREFIX_STRIP"   => env("DKW_IMAGE_URL_PREFIX_STRIP"),

    // ---- Allow MP3 and MP4 to access the underlying file system without image hashing
    //
    //      USE WITH CAUTION
    //
    "DIRECT_ACCESS_TO_MP3_MP4" => env("DKW_DIRECT_ACCESS_TO_MP3_MP4", FALSE),

    // ---- Flag to show/hide the main menu
    //
    "SHOW_MAIN_MENU"           => env("DKW_SHOW_MAIN_MENU", FALSE),
];
