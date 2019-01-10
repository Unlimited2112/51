using System;
using System.Windows.Forms;

namespace Hotel.Forms
{
    public partial class MainForm : Form
    {
        public MainForm()
        {
            InitializeComponent();
        }

        private void MainForm_Load(object sender, EventArgs e)
        {
            RefreshTable();
            if (Program.roleId != 1)
            {
                menuUsers.Visible = false;
            }
        }

        private void RefreshTable()
        {
            HotelDataSetTableAdapters.MovesViewTableAdapter ta = new HotelDataSetTableAdapters.MovesViewTableAdapter();
            movesViewBindingSource.DataSource = ta.GetData();
        }

        private void menuTypes_Click(object sender, EventArgs e)
        {
            TypesList frm = new TypesList();
            frm.ShowDialog();
        }

        private void menuRooms_Click(object sender, EventArgs e)
        {
            RoomsList frm = new RoomsList();
            frm.ShowDialog();
        }

        private void menuUsers_Click(object sender, EventArgs e)
        {
            UsersList frm = new UsersList();
            frm.ShowDialog();
        }

        private void menuMoveNew_Click(object sender, EventArgs e)
        {
            MoveForm frm = new MoveForm(-1);
            frm.ShowDialog();
            RefreshTable();
        }

        private void menuMoveEdit_Click(object sender, EventArgs e)
        {
            int itemId = (int)dgvMain["idDataGridViewTextBoxColumn", dgvMain.CurrentRow.Index].Value;
            MoveForm frm = new MoveForm(itemId);
            frm.ShowDialog();
            RefreshTable();
        }

        private void menuMoveDel_Click(object sender, EventArgs e)
        {
            int itemId = (int)dgvMain["idDataGridViewTextBoxColumn", dgvMain.CurrentRow.Index].Value;
            if (MessageBox.Show(
                    "Вы действительно хотите удалить элемент?",
                    "Подтверждение удаления",
                    MessageBoxButtons.YesNo,
                    MessageBoxIcon.Warning,
                    MessageBoxDefaultButton.Button2
                ) == DialogResult.Yes)
            {
                HotelDataSetTableAdapters.MovesViewTableAdapter ta = new HotelDataSetTableAdapters.MovesViewTableAdapter();
                ta.DeleteQuery(itemId);
                RefreshTable();
            }
        }

        private void menuReportPersonCnt_Click(object sender, EventArgs e)
        {
            Reports.RPersonsForm frm = new Reports.RPersonsForm();
            frm.ShowDialog();
        }

        private void menuReportReservation_Click(object sender, EventArgs e)
        {
            Reports.RReservationForm frm = new Reports.RReservationForm();
            frm.ShowDialog();
        }

        private void menuReportRoomType_Click(object sender, EventArgs e)
        {
            Reports.RRoomTypeForm frm = new Reports.RRoomTypeForm();
            frm.ShowDialog();
        }
    }
}
