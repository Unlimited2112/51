using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using ForumSimple.Helpers;

namespace ForumSimple
{
    // Страница авторизации
    public partial class Auth : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            // Если пользователь авторизован, редирект на главную
            if (Context.User.Identity.IsAuthenticated)
                Response.Redirect("Default.aspx");
        }

        protected void BtnLogin_Click(object sender, EventArgs e)
        {
            // Проверяем результат авторизации
            if (LoginHelper.LoginUser(Response, Login.Text, Password.Text))
                // Если пользователь авторизован, редирект на главную
                Response.Redirect("Default.aspx");
            else
                // Если не авторизован, выводим сообщение об ошибке
                LiteralResult.Text = "Авторизация неудачна!";
        }
    }
}