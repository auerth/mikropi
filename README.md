# mikropi

Mikropi is an e-learning software solution that supports students in the preparation and follow-up of lectures and exam preparation. The software is suitable for displaying microscope sections.
## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.


### Installing
A step by step series of examples that tell you how to get a development env running

Run an Apache/NginX Server with PHP7 and Database on an Linux System

Clone the whole repository to your web folder

Install LibVips for Linux ( https://github.com/libvips/libvips )

```
sudo apt install libvips libvips-dev
```
Import Database Structure to Databse ([Structure Location](etc/usr_web0_1.sql))

Create Folder files/moduls/ in web folder root

Create Folder files/cuts/ in web folder root

Rename etc/db_.php to etc/db.php and edit login credentials

Register your first account on [yourdomain]/register.php

Go to database and activate account in table "user"

Write the id of your user in table "admin"

Now upload Cut Slides in Tiff Format on webpage ([yourdomain]/admin.php)

## Built With

* [libvips](https://github.com/libvips/libvips) - Tiff converter to Slide Format
* [Bootstrap](https://getbootstrap.com/) - Web Design
* [Openseadragon](https://openseadragon.github.io/) - An open-source, web-based viewer for high-resolution zoomable images, implemented in pure JavaScript, for desktop and mobile. 
* [Font Awesome](https://rometools.github.io/rome/) - Used for Icons

## Authors

* **Class documentation** - [Softwelop](https://dev.softwelop.com/mikropi)

## Authors

* **Thorben Auer** - *Developer* - [auerth](https://github.com/auerth)

## License

This project is licensed under the MIT License


