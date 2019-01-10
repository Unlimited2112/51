using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using ForumSimple.Helpers;

namespace ForumSimple
{
    // Страница регистрации пользователя
    public partial class Register : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            // Если пользователь авторизован - редирект на главную
            if (Context.User.Identity.IsAuthenticated)
                Response.Redirect("Default.aspx");
        }
        // Обработка регистрации
        protected void BtnRegister_Click(object sender, EventArgs e)
        {
            // Если логин проходит валидацию на отсутствие в БД
            if (LoginHelper.ValidateLogin(Login.Text))
            {
                // Регистрируем нового пользователя и делаем редирект на главную
                LoginHelper.RegisterUser(Response, Login.Text, Password.Text);
                Response.Redirect("Default.aspx");
            }
        }
        // Валидация логина пользователя на отстутствие в БД
        protected void cstmLogin_ServerValidate(object source, ServerValidateEventArgs args)
        {
            args.IsValid = LoginHelper.ValidateLogin(Login.Text);
        }
    }
}