create database if not exists bd_glark;
use bd_glark;

create table tb_usuario(
	cd_usuario int not null auto_increment primary key,
    ds_email varchar (50),
    ds_senha varchar (128) not null,
    nm_user varchar(50),
    sg_sexoUser enum('M', 'F'),
    qt_idadeUser int check(qt_idadeUser >= 18 and qt_idadeUser <= 100)
);

insert into tb_usuario(
    ds_email,
    ds_senha
)
values
('andre.silva@gmail.com', '123456');


select tb_usuario.ds_email, tb_usuario.ds_senha from tb_usuario;
