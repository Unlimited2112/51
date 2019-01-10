using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

namespace ForumSimple
{
    // Страница списка пользователей
    public partial class Users : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            // Если не админ - редирект на главную
            if (!HttpContext.Current.User.IsInRole("Admin"))
                Response.Redirect("Default.aspx");
        }
    }
}