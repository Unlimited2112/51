using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using ForumSimple.Helpers;

namespace ForumSimple
{
    // Страница профиля пользователя. Позволяет:
    // 1) админу и модератору менять роль
    // 2) пользователю изменять свой пароль
    public partial class Profile : System.Web.UI.Page
    {
        // Данные редактируемого и текущего пользователей
        User editUser, currentUser;
        protected void Page_Load(object sender, EventArgs e)
        {
            // Извлекаем идентификатор пользователя из GET-параметра
            // и определяем по нему редактируемого пользователя
            int userID = 0;
            try { userID = Convert.ToInt32(Request.QueryString["UserID"]); }
            catch { }
            editUser = new User(userID);
            // Извлекаем из данных авторизации логин
            // и определяем по нему текущего пользователя
            string login = HttpContext.Current.User.Identity.Name;
            currentUser = new User(login);
            // Если редактируемый пользователь определен
            if (editUser.id > 0)
            {
                // выводим его логин в заголовок и титул
                literalUserName.Text = editUser.login;
                this.Title = editUser.login + " - профиль";
                // если редактируемый и текущий пользователь совпадают - открываем возможность смены пароля
                if (currentUser.id == editUser.id)
                    passwordPanel.Visible = true;
                else
                    passwordPanel.Visible = false;
            }
            // иначе - редирект на главную
            else
                Response.Redirect("Default.aspx");
        }
        // Обработка сохранения роли пользователя
        protected void BtnRuleSave_Click(object sender, EventArgs e)
        {
            int rules = Int32.Parse(rulesRadioButtonList.SelectedItem.Value);
            ForumDataSetTableAdapters.userViewTableAdapter ta = new ForumDataSetTableAdapters.userViewTableAdapter();
            ta.UpdateRules(rules, editUser.id);
            editUser = new User(editUser.id);
            LiteralRulesResult.Text = "Роль изменена!";
        }
        // Обработка сохранения измененного пароля
        protected void BtnPasswordSave_Click(object sender, EventArgs e)
        {
            if (cstmPasswordOld.IsValid)
            {
                ForumDataSetTableAdapters.userViewTableAdapter ta = new ForumDataSetTableAdapters.userViewTableAdapter();
                ta.UpdatePassword(LoginHelper.GetHash(Password.Text), editUser.id);
                editUser = new User(editUser.id);
                LiteralPasswordResult.Text = "Пароль изменен!";
            }
        }
        // Валидация старого пароля
        protected void cstmPasswordOld_ServerValidate(object source, ServerValidateEventArgs args)
        {
            if (LoginHelper.GetHash(PasswordOld.Text) == editUser.password)
                args.IsValid = true;
            else
                args.IsValid = false;
        }
        // Формирование списка доступных ролей:
        // 1) администратор может установить роли: модератор, пользователь, в бане
        // 2) модератор может установить роли: пользователь, в бане
        protected void rulesPanel_PreRender(object sender, EventArgs e)
        {
            // Очищаем список ролей
            rulesRadioButtonList.Items.Clear();
            // Если текущий пользователь админ и редактирует не профиль админа, то открываем возможность изменять роли
            // Если текущий пользователь модератор и редактирует профили пользователя или забаненного, то открываем возможность изменять роли
            if ((currentUser.rules == 1 && editUser.rules > 1) || (currentUser.rules == 2 && editUser.rules > 2))
            {
                rulesPanel.Visible = true;
                // если текущий пользователь админ
                // добавляем в список роль модератора
                if (currentUser.rules == 1)
                    rulesRadioButtonList.Items.Add(new ListItem("Модератор", "2"));
                // добавляем в список роль пользователя
                rulesRadioButtonList.Items.Add(new ListItem("Пользователь", "3"));
                // добавляем в список роль забаненного
                rulesRadioButtonList.Items.Add(new ListItem("В бане", "4"));
                // отмечаем в списке роль текущего пользователя
                rulesRadioButtonList.SelectedValue = editUser.rules.ToString();
            }
            else
                rulesPanel.Visible = false;
        }

    }
}