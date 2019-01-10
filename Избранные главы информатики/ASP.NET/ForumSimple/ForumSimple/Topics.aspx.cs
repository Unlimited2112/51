using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Data;
using ForumSimple.Helpers;

namespace ForumSimple
{
    // Страница списка тем раздела
    public partial class Topics : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            // Определяем авторизованного пользователя по логину
            // Помещаем идентификатор пользователя в скрытое поле страницы,
            // чтобы использовать при добавлении данных через ListView
            string login = HttpContext.Current.User.Identity.Name;
            User user = new User(login);
            hiddenUserID.Text = user.id.ToString();
            // Извлекаем из GET-параметра идентификатор раздела
            // Помещаем идентификатор раздела в скрытое поле страницы,
            // чтобы использовать при выборке из БД и добавлении данных через ListView
            int rubricID = 0;
            try { rubricID = Convert.ToInt32(Request.QueryString["RubricID"]); }
            catch { }
            hiddenRubricID.Text = rubricID.ToString();
            // Получаем из БД название раздела и вставляем в соответствующие поля
            ForumDataSetTableAdapters.rubricsTableAdapter ta = new ForumDataSetTableAdapters.rubricsTableAdapter();
            DataTable dt = ta.GetDataById(rubricID);
            string rubricName = "Темы раздела";
            if (dt.Rows.Count > 0)
            {
                rubricName = dt.Rows[0]["name"].ToString();
            }
            literalRubricName.Text = rubricName;
            this.Title = rubricName;
        }
        // Проверка права на добавление тем
        protected bool AllowInsert()
        {
            if (HttpContext.Current.User.IsInRole("Admin") || HttpContext.Current.User.IsInRole("Moder") || HttpContext.Current.User.IsInRole("User"))
                return true;
            else
                return false;
        }

    }
}