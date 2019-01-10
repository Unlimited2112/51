using System;
using System.Windows.Forms;

namespace Hotel
{
    public partial class AuthForm : Form
    {
        public AuthForm()
        {
            InitializeComponent();
        }

        private void AuthForm_Load(object sender, EventArgs e)
        {
            HotelDataSetTableAdapters.UsersViewTableAdapter ta = new HotelDataSetTableAdapters.UsersViewTableAdapter();
            usersViewBindingSource.DataSource = ta.GetData();
        }

        private void btnOk_Click(object sender, EventArgs e)
        {
            HotelDataSetTableAdapters.ScalarQueriesTableAdapter ta = new HotelDataSetTableAdapters.ScalarQueriesTableAdapter();
            int userId = (int)fldName.SelectedValue;
            int? roleId = ta.RoleIdByUserData(userId, fldPassword.Text);
            if (roleId != null)
            {
                this.Hide();
                Program.userId = userId;
                Program.roleId = (int)roleId;
                Forms.MainForm frm = new Forms.MainForm();
                frm.ShowDialog();
                this.Close();
            }
            else
            {
                MessageBox.Show(
                        "Авторизация неудачна",
                        "Внимание",
                        MessageBoxButtons.OK,
                        MessageBoxIcon.Warning
                    );
            }
        }

        private void btnCancel_Click(object sender, EventArgs e)
        {
            this.Close();
        }
    }
}
