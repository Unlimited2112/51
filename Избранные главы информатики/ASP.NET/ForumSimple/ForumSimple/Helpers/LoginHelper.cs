using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Text;
using System.Security.Cryptography;
using System.Web.Security;

namespace ForumSimple.Helpers
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
        private static void SetTicket(HttpResponse response, User user)
        {
            FormsAuthenticationTicket ticket = new FormsAuthenticationTicket(1, user.login, DateTime.Now, DateTime.Now.AddDays(7), true, user.role);
            string strEncrypted = FormsAuthentication.Encrypt(ticket);
            response.Cookies.Add(new HttpCookie("__AUTH", strEncrypted));
        }
        // Проверка логина на присутствие в БД
        public static bool ValidateLogin(string login)
        {
            if (login == "Guest")
                return false;
            ForumDataSetTableAdapters.userViewTableAdapter ta = new ForumDataSetTableAdapters.userViewTableAdapter();
            int cnt = (int)ta.CountByLogin(login);
            if (cnt > 0)
                return false;
            else
                return true;
        }
        // Возврат результата авторизации по данным из БД
        public static bool LoginUser(HttpResponse response, string login, string pswrd)
        {
            var user = new User(login, GetHash(pswrd));
            if (user.id > 0)
            {
                SetTicket(response, user);
                return true;
            }
            else
                return false;
        }
        // Возврат результата регистрации с записью в БД
        public static bool RegisterUser(HttpResponse response, string login, string pswrd)
        {
            if (ValidateLogin(login))
            {
                User user = new User(-1, login, GetHash(pswrd), 3);
                user.Save();
                user = new User(login);
                if (user.id > 0)
                {
                    SetTicket(response, user);
                    return true;
                }
                else
                    return false;
            }
            else
                return false;
        }

    }
}