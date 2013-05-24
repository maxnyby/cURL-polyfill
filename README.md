cURL-polyfill
=============

A polyfill for php-servers without cURL-support


Why?
---

The other day I had to make use of a library that used cURL on a server that didn't have it installed. I didn't want to rewrite the complete library, so in stead I decided to make this polyfill.

Right now it is extremely basic, only taking into account very simple cURL-commands, but it is a work in progress.

How to use it?
---

Simply load the file before trying to use curl_init or any other cURL-function. And don't worry about loading the library on a server WITH cURL-support, it checks for support before doing anything.
