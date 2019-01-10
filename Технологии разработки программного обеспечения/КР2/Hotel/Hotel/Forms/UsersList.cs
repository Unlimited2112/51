using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace Hotel.Forms
{
    public partial class UsersList : Form
    {
        public UsersList()
        {
            InitializeComponent();
        }

        private void UsersList_Load(object sender, EventArgs e)
        {
            RefreshTable();
        }

        private void RefreshTable()
        {
            HotelDataSetTableAdapters.UsersViewTableAdapter ta = new HotelDataSetTableAdapters.UsersViewTableAdapter();
            tableBindingSource.DataSource = ta.GetData();
        }

        private void menuNew_Click(object sender, EventArgs e)
        {
            UserForm frm = new UserForm(-1);
            frm.ShowDialog();
            RefreshTable();
        }

        private void menuEdit_Click(object sender, EventArgs e)
        {
            int itemId = (int)dgvMain["idDataGridViewTextBoxColumn", dgvMain.CurrentRow.Index].Value;
            UserForm frm = new UserForm(itemId);
            frm.ShowDialog();
            RefreshTable();
        }

        private void menuDelete_Click(object sender, EventArgs e)
        {
            int itemId = (int)dgvMain["idDataGridViewTextBoxColumn", dgvMain.CurrentRow.Index].Value;
            if (itemId == Program.userId)
            {
                MessageBox.Show(
                        "Нельзя удалить собственную запись!",
                        "Внимание!",
                        MessageBoxButtons.OK,
                        MessageBoxIcon.Error
                    );
                return;
            }
            if (MessageBox.Show(
                    "Вы действительно хотите удалить элемент?",
                    "Подтверждение удаления",
                    MessageBoxButtons.YesNo,
                    MessageBoxIcon.Warning,
                    MessageBoxDefaultButton.Button2
                ) == DialogResult.Yes)
            {
                HotelDataSetTableAdapters.ScalarQueriesTableAdapter taScalar = new HotelDataSetTableAdapters.ScalarQueriesTableAdapter();
                if ((int)taScalar.CntMovesByUser(itemId) > 0)
                    MessageBox.Show(
                        "Нельзя удалить элемент, на который имеются ссылки!",
                        "Внимание!",
                        MessageBoxButtons.OK,
                        MessageBoxIcon.Error
                    );
                else
                {
                    HotelDataSetTableAdapters.UsersViewTableAdapter ta = new HotelDataSetTableAdapters.UsersViewTableAdapter();
                    ta.DeleteQuery(itemId);
                    RefreshTable();
                }
            }
        }
    }
}
