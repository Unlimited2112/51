using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.ComponentModel.DataAnnotations;
using System.Web.Mvc;

namespace ForumMVC.Models
{
    // Основная модель для БД
    public class User
    {
        public User()
        {
            Topics = new HashSet<Topic>();
            Messages = new HashSet<Message>();
        }
        public int Id { get; set; }
        public string Login { get; set; }
        public string Password { get; set; }
        public int? RoleId { get; set; }
        public virtual Role Role { get; set; }
        public virtual ICollection<Topic> Topics { get; set; }
        public virtual ICollection<Message> Messages { get; set; }
    }
    // Вспомогательная модель для авторизации
    public class LogOnModel
    {
        [Required] // Для валидации обязательного заполнения
        [Display(Name = "Логин")] // ДЛя вывода label
        public string UserName { get; set; }
        [Required]
        [DataType(DataType.Password)] // Тип поля - пароль
        [Display(Name = "Пароль")]
        public string Password { get; set; }
    }
    // Вспомогательная модель для регистрации
    public class RegisterModel
    {
        [Required]
        [RegularExpression(@"^[a-zA-Z][a-zA-Z0-9-_\.]{1,20}$", ErrorMessage = "Логин может включать 2-20 букв и цифр, должен начинаться с буквы")] // Для валидации по регулярному выражению
        [Remote("CheckUserName", "Account", ErrorMessage = "Такой логин уже существует!")] // Для валидации на существование такого логина
        [Display(Name = "Логин")]
        public string UserName { get; set; }
        [Required]
        [DataType(DataType.Password)]
        [Display(Name = "Пароль")]
        public string Password { get; set; }
        [DataType(DataType.Password)]
        [Display(Name = "Подтверждение пароля")]
        [Compare("Password", ErrorMessage = "Пароль и его подтверждение не совпадают!")] // Для валидации на совпадение
        public string PasswordConfirm { get; set; }
    }
    // Вспомогательная модель для изменения пароля
    public class ChangePasswordModel
    {
        public int UserId { get; set; }
        [Required]
        [DataType(DataType.Password)]
        [Display(Name = "Текущий пароль")]
        public string PasswordOld { get; set; }
        [Required]
        [DataType(DataType.Password)]
        [Display(Name = "Новый пароль")]
        public string Password { get; set; }
        [DataType(DataType.Password)]
        [Display(Name = "Подтверждение пароля")]
        [Compare("Password", ErrorMessage = "Новый пароль и его подтверждение не совпадают.")]
        public string PasswordConfirm { get; set; }
    }
    // Вспомогательная модель для изменения роли
    public class ChangeRoleModel
    {
        public int UserId { get; set; }
        [Required]
        [Display(Name = "Роль пользователя")]
        public int RoleId { get; set; }
    }
}