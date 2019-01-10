using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Data;

namespace ForumSimple.Helpers
{
    // Класс с данными пользователя. Выделили в отдельный класс, т.к. используем собственную схему авторизации
    public class User
    {
        public int id { get; set; }
        public string login { get; set; }
        public string password { get; set; }
        public int rules { get; set; }
        public string role { get; set; }
        // Данные для неавторизованного пользователя
        private void SetDefault()
        {
            this.id = -1;
            this.login = "Guest";
            this.password = "1";
            this.rules = 5;
            SetRole();
        }
        // Дефолтная инициализация
        public User()
        {
            SetDefault();
        }
        // Инициализация с указанием всех значений
        // Используем при регистрации
        public User(int id, string login, string password, int rules)
        {
            this.id = id;
            this.login = login;
            this.password = password;
            this.rules = rules;
            SetRole();
        }
        // Инициализация по логину с загрузкой из БД
        // Используем для проверки прав
        public User(string login)
        {
            ForumDataSetTableAdapters.userViewTableAdapter ta = new ForumDataSetTableAdapters.userViewTableAdapter();
            DataTable dt = ta.GetDataByLogin(login);
            if (dt.Rows.Count > 0)
            {
                DataRow row = dt.Rows[0];
                this.id = (int)row["id"];
                this.login = (string)row["login"];
                this.password = (string)row["password"];
                this.rules = (int)row["rules"];
                SetRole();
            }
            else
                SetDefault();
        }
        // Инициализация по логину и паролю с загрузкой из БД
        // Используем при авторизации
        public User(string login, string password)
        {
            ForumDataSetTableAdapters.userViewTableAdapter ta = new ForumDataSetTableAdapters.userViewTableAdapter();
            DataTable dt = ta.GetDataByLoginPassword(login, password);
            if (dt.Rows.Count > 0)
            {
                DataRow row = dt.Rows[0];
                this.id = (int)row["id"];
                this.login = (string)row["login"];
                this.password = (string)row["password"];
                this.rules = (int)row["rules"];
                SetRole();
            }
            else
                SetDefault();
        }
        // Инициализация по идентификатору
        // Используем при изменении пароля и роли
        public User(int id)
        {
            ForumDataSetTableAdapters.userViewTableAdapter ta = new ForumDataSetTableAdapters.userViewTableAdapter();
            DataTable dt = ta.GetDataById(id);
            if (dt.Rows.Count > 0)
            {
                DataRow row = dt.Rows[0];
                this.id = (int)row["id"];
                this.login = (string)row["login"];
                this.password = (string)row["password"];
                this.rules = (int)row["rules"];
                SetRole();
            }
            else
                SetDefault();
        }
        // Сохранение данных в БД
        public string Save()
        {
            ForumDataSetTableAdapters.userViewTableAdapter ta = new ForumDataSetTableAdapters.userViewTableAdapter();
            if (id == -1)
                return ta.InsertQuery(login, password, rules).ToString();
            else
                return ta.UpdateQuery(password, rules, id).ToString();
        }
        // Определяет строковый идентификатор роли
        private void SetRole()
        {
            switch (rules)
            {
                case 1:
                    role = "Admin";
                    break;
                case 2:
                    role = "Moder";
                    break;
                case 3:
                    role = "User";
                    break;
                case 4:
                    role = "Ban";
                    break;
                default:
                    role = "Guest";
                    break;
            }
        }
    }
}