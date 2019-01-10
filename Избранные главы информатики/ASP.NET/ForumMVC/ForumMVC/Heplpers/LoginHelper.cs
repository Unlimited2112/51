using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Text;
using System.Security.Cryptography;
using System.Web.Security;
using ForumMVC.Models;

namespace ForumMVC.Helpers
{
    // Вспомогательные методы для работы с данными пользователей
    public static class LoginHelper
    {
        // Формирование хеша из строки
        public static string GetHash(string str)
        {
            byte[] bytes = Encoding.Unicode.GetBytes(str);
            var csp = new MD5CryptoServiceProvider();
            byte[] byteHash = csp.ComputeHash(bytes);
            string hash = string.Empty;
            foreach (byte b in byteHash)
                hash += string.Format("{0:x2}", b);
            return hash;
        }
        // Запись билета аутентификации
        private static void SetTicket(HttpResponseBase response, User user)
        {
            FormsAuthenticationTicket ticket = new FormsAuthenticationTicket(1, user.Login, DateTime.Now, DateTime.Now.AddDays(7), true, user.Role.Name);
            string strEncrypted = FormsAuthentication.Encrypt(ticket);
            response.Cookies.Add(new HttpCookie("__AUTH", strEncrypted));
        }
        // Проверка логина на присутствие в БД
        public static bool ValidateLogin(string login)
        {
            bool isValid = true;
            using (ForumContext _db = new ForumContext())
            {
                User user = (from u in _db.Users
                              where u.Login == login
                              select u).FirstOrDefault();
                if (user != null)
                    isValid = false;
            }
            return isValid;
        }
        // Возврат результата авторизации по данным из БД
        public static bool LoginUser(HttpResponseBase response, string login, string pswrd)
        {
            bool isValid = false;
            using (ForumContext _db = new ForumContext())
            {
                User user = (from u in _db.Users
                              where (u.Login == login)
                              select u).FirstOrDefault();
                if (user != null && user.Password == GetHash(pswrd))
                {
                    SetTicket(response, user);
                    isValid = true;
                }
            }
            return isValid;
        }
        // Возврат результата регистрации с записью в БД
        public static bool RegisterUser(HttpResponseBase response, string login, string pswrd)
        {
            bool isValid = false;
            if (ValidateLogin(login))
            {
                using (ForumContext _db = new ForumContext())
                {
                    User user = new User();
                    user.Login = login;
                    user.Password = GetHash(pswrd);
                    user.Role = _db.Roles.FirstOrDefault(p => p.Id == 3);
                    _db.Users.Add(user);
                    _db.SaveChanges();
                    user = (from u in _db.Users
                            where (u.Login == login)
                            select u).FirstOrDefault();
                    if (user != null)
                    {
                        SetTicket(response, user);
                        isValid = true;
                    }
                }
            }
            return isValid;
        }

    }
}