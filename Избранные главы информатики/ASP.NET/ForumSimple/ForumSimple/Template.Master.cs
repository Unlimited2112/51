using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using ForumSimple.Helpers;
using System.Web.Security;

namespace ForumSimple
{
    // Мастер-страница
    public partial class Template : System.Web.UI.MasterPage
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            // Получаем логин авторизованного пользователя
            string login = HttpContext.Current.User.Identity.Name;
            User user = new User(login);
            // Если пользователь авторизован
            if (Context.User.Identity.IsAuthenticated)
            {
                // Помещаем данные пользователя в соответствующие поля
                // и показываем панель пользователя
                profileHyperLink.Text = user.login;
                profileHyperLink.NavigateUrl = string.Format("~/Profile.aspx?UserID={0}", user.id);
                userNamePanel.Visible = true;
            }
            // Иначе - скрываем панель данных пользователя
            else
                userNamePanel.Visible = false;
        }
        // Обработка выхода авторизованного пользователя
        protected void menuLogout_Click(object sender, EventArgs e)
        {
            FormsAuthentication.SignOut();
            Response.Redirect("Default.aspx");
        }
    }
}