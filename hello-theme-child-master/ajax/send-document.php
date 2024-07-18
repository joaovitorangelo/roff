<?php
defined( 'ABSPATH' ) || exit;
function document_form( $fields ) {
  $document_type = $fields['document_type'];
  $document_archive = $fields['document_archive'];
  return array( 
    'document_type'     => $document_type,
    'document_archive'  => $document_archive,
  );
}