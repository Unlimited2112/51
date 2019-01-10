namespace Hotel.Forms
{
    partial class MainForm
    {
        /// <summary>
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            this.components = new System.ComponentModel.Container();
            this.menuStrip1 = new System.Windows.Forms.MenuStrip();
            this.menuRef = new System.Windows.Forms.ToolStripMenuItem();
            this.menuTypes = new System.Windows.Forms.ToolStripMenuItem();
            this.menuRooms = new System.Windows.Forms.ToolStripMenuItem();
            this.menuUsers = new System.Windows.Forms.ToolStripMenuItem();
            this.menuMoves = new System.Windows.Forms.ToolStripMenuItem();
            this.menuMoveNew = new System.Windows.Forms.ToolStripMenuItem();
            this.menuMoveEdit = new System.Windows.Forms.ToolStripMenuItem();
            this.menuMoveDel = new System.Windows.Forms.ToolStripMenuItem();
            this.menuReports = new System.Windows.Forms.ToolStripMenuItem();
            this.dgvMain = new System.Windows.Forms.DataGridView();
            this.idDataGridViewTextBoxColumn = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.datesDataGridViewTextBoxColumn = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.actionNameDataGridViewTextBoxColumn = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.roomIdDataGridViewTextBoxColumn = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.datesInDataGridViewTextBoxColumn = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.daysCntDataGridViewTextBoxColumn = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.datesOutDataGridViewTextBoxColumn = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.personsCntDataGridViewTextBoxColumn = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.userNameDataGridViewTextBoxColumn = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.movesViewBindingSource = new System.Windows.Forms.BindingSource(this.components);
            this.hotelDataSet = new Hotel.HotelDataSet();
            this.menuReportPersonCnt = new System.Windows.Forms.ToolStripMenuItem();
            this.menuReportReservation = new System.Windows.Forms.ToolStripMenuItem();
            this.menuReportRoomType = new System.Windows.Forms.ToolStripMenuItem();
            this.menuStrip1.SuspendLayout();
            ((System.ComponentModel.ISupportInitialize)(this.dgvMain)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.movesViewBindingSource)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.hotelDataSet)).BeginInit();
            this.SuspendLayout();
            // 
            // menuStrip1
            // 
            this.menuStrip1.Items.AddRange(new System.Windows.Forms.ToolStripItem[] {
            this.menuRef,
            this.menuMoves,
            this.menuReports});
            this.menuStrip1.Location = new System.Drawing.Point(0, 0);
            this.menuStrip1.Name = "menuStrip1";
            this.menuStrip1.Size = new System.Drawing.Size(584, 24);
            this.menuStrip1.TabIndex = 0;
            this.menuStrip1.Text = "Главное меню";
            // 
            // menuRef
            // 
            this.menuRef.DropDownItems.AddRange(new System.Windows.Forms.ToolStripItem[] {
            this.menuTypes,
            this.menuRooms,
            this.menuUsers});
            this.menuRef.Name = "menuRef";
            this.menuRef.Size = new System.Drawing.Size(94, 20);
            this.menuRef.Text = "Справочники";
            // 
            // menuTypes
            // 
            this.menuTypes.Name = "menuTypes";
            this.menuTypes.Size = new System.Drawing.Size(156, 22);
            this.menuTypes.Text = "Типы номеров";
            this.menuTypes.Click += new System.EventHandler(this.menuTypes_Click);
            // 
            // menuRooms
            // 
            this.menuRooms.Name = "menuRooms";
            this.menuRooms.Size = new System.Drawing.Size(156, 22);
            this.menuRooms.Text = "Номера";
            this.menuRooms.Click += new System.EventHandler(this.menuRooms_Click);
            // 
            // menuUsers
            // 
            this.menuUsers.Name = "menuUsers";
            this.menuUsers.Size = new System.Drawing.Size(156, 22);
            this.menuUsers.Text = "Пользователи";
            this.menuUsers.Click += new System.EventHandler(this.menuUsers_Click);
            // 
            // menuMoves
            // 
            this.menuMoves.DropDownItems.AddRange(new System.Windows.Forms.ToolStripItem[] {
            this.menuMoveNew,
            this.menuMoveEdit,
            this.menuMoveDel});
            this.menuMoves.Name = "menuMoves";
            this.menuMoves.Size = new System.Drawing.Size(96, 20);
            this.menuMoves.Text = "Журнал учета";
            // 
            // menuMoveNew
            // 
            this.menuMoveNew.Name = "menuMoveNew";
            this.menuMoveNew.Size = new System.Drawing.Size(168, 22);
            this.menuMoveNew.Text = "Добавить запись";
            this.menuMoveNew.Click += new System.EventHandler(this.menuMoveNew_Click);
            // 
            // menuMoveEdit
            // 
            this.menuMoveEdit.Name = "menuMoveEdit";
            this.menuMoveEdit.Size = new System.Drawing.Size(168, 22);
            this.menuMoveEdit.Text = "Изменить запись";
            this.menuMoveEdit.Click += new System.EventHandler(this.menuMoveEdit_Click);
            // 
            // menuMoveDel
            // 
            this.menuMoveDel.Name = "menuMoveDel";
            this.menuMoveDel.Size = new System.Drawing.Size(168, 22);
            this.menuMoveDel.Text = "Удалить запись";
            this.menuMoveDel.Click += new System.EventHandler(this.menuMoveDel_Click);
            // 
            // menuReports
            // 
            this.menuReports.DropDownItems.AddRange(new System.Windows.Forms.ToolStripItem[] {
            this.menuReportPersonCnt,
            this.menuReportReservation,
            this.menuReportRoomType});
            this.menuReports.Name = "menuReports";
            this.menuReports.Size = new System.Drawing.Size(60, 20);
            this.menuReports.Text = "Отчеты";
            // 
            // dgvMain
            // 
            this.dgvMain.AllowUserToAddRows = false;
            this.dgvMain.AllowUserToDeleteRows = false;
            this.dgvMain.AutoGenerateColumns = false;
            this.dgvMain.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize;
            this.dgvMain.Columns.AddRange(new System.Windows.Forms.DataGridViewColumn[] {
            this.idDataGridViewTextBoxColumn,
            this.datesDataGridViewTextBoxColumn,
            this.actionNameDataGridViewTextBoxColumn,
            this.roomIdDataGridViewTextBoxColumn,
            this.datesInDataGridViewTextBoxColumn,
            this.daysCntDataGridViewTextBoxColumn,
            this.datesOutDataGridViewTextBoxColumn,
            this.personsCntDataGridViewTextBoxColumn,
            this.userNameDataGridViewTextBoxColumn});
            this.dgvMain.DataSource = this.movesViewBindingSource;
            this.dgvMain.Dock = System.Windows.Forms.DockStyle.Fill;
            this.dgvMain.Location = new System.Drawing.Point(0, 24);
            this.dgvMain.Name = "dgvMain";
            this.dgvMain.ReadOnly = true;
            this.dgvMain.Size = new System.Drawing.Size(584, 337);
            this.dgvMain.TabIndex = 1;
            // 
            // idDataGridViewTextBoxColumn
            // 
            this.idDataGridViewTextBoxColumn.DataPropertyName = "Id";
            this.idDataGridViewTextBoxColumn.HeaderText = "Код";
            this.idDataGridViewTextBoxColumn.Name = "idDataGridViewTextBoxColumn";
            this.idDataGridViewTextBoxColumn.ReadOnly = true;
            this.idDataGridViewTextBoxColumn.Width = 50;
            // 
            // datesDataGridViewTextBoxColumn
            // 
            this.datesDataGridViewTextBoxColumn.DataPropertyName = "Dates";
            this.datesDataGridViewTextBoxColumn.HeaderText = "Дата дв.";
            this.datesDataGridViewTextBoxColumn.Name = "datesDataGridViewTextBoxColumn";
            this.datesDataGridViewTextBoxColumn.ReadOnly = true;
            // 
            // actionNameDataGridViewTextBoxColumn
            // 
            this.actionNameDataGridViewTextBoxColumn.DataPropertyName = "ActionName";
            this.actionNameDataGridViewTextBoxColumn.HeaderText = "Движение";
            this.actionNameDataGridViewTextBoxColumn.Name = "actionNameDataGridViewTextBoxColumn";
            this.actionNameDataGridViewTextBoxColumn.ReadOnly = true;
            // 
            // roomIdDataGridViewTextBoxColumn
            // 
            this.roomIdDataGridViewTextBoxColumn.DataPropertyName = "RoomId";
            this.roomIdDataGridViewTextBoxColumn.HeaderText = "Номер";
            this.roomIdDataGridViewTextBoxColumn.Name = "roomIdDataGridViewTextBoxColumn";
            this.roomIdDataGridViewTextBoxColumn.ReadOnly = true;
            this.roomIdDataGridViewTextBoxColumn.Width = 75;
            // 
            // datesInDataGridViewTextBoxColumn
            // 
            this.datesInDataGridViewTextBoxColumn.DataPropertyName = "DatesIn";
            this.datesInDataGridViewTextBoxColumn.HeaderText = "Дата нач.";
            this.datesInDataGridViewTextBoxColumn.Name = "datesInDataGridViewTextBoxColumn";
            this.datesInDataGridViewTextBoxColumn.ReadOnly = true;
            // 
            // daysCntDataGridViewTextBoxColumn
            // 
            this.daysCntDataGridViewTextBoxColumn.DataPropertyName = "DaysCnt";
            this.daysCntDataGridViewTextBoxColumn.HeaderText = "Дни";
            this.daysCntDataGridViewTextBoxColumn.Name = "daysCntDataGridViewTextBoxColumn";
            this.daysCntDataGridViewTextBoxColumn.ReadOnly = true;
            this.daysCntDataGridViewTextBoxColumn.Width = 50;
            // 
            // datesOutDataGridViewTextBoxColumn
            // 
            this.datesOutDataGridViewTextBoxColumn.DataPropertyName = "DatesOut";
            this.datesOutDataGridViewTextBoxColumn.HeaderText = "Дата кон.";
            this.datesOutDataGridViewTextBoxColumn.Name = "datesOutDataGridViewTextBoxColumn";
            this.datesOutDataGridViewTextBoxColumn.ReadOnly = true;
            // 
            // personsCntDataGridViewTextBoxColumn
            // 
            this.personsCntDataGridViewTextBoxColumn.DataPropertyName = "PersonsCnt";
            this.personsCntDataGridViewTextBoxColumn.HeaderText = "Чел.";
            this.personsCntDataGridViewTextBoxColumn.Name = "personsCntDataGridViewTextBoxColumn";
            this.personsCntDataGridViewTextBoxColumn.ReadOnly = true;
            this.personsCntDataGridViewTextBoxColumn.Width = 50;
            // 
            // userNameDataGridViewTextBoxColumn
            // 
            this.userNameDataGridViewTextBoxColumn.DataPropertyName = "UserName";
            this.userNameDataGridViewTextBoxColumn.HeaderText = "Сотрудник";
            this.userNameDataGridViewTextBoxColumn.Name = "userNameDataGridViewTextBoxColumn";
            this.userNameDataGridViewTextBoxColumn.ReadOnly = true;
            // 
            // movesViewBindingSource
            // 
            this.movesViewBindingSource.DataMember = "MovesView";
            this.movesViewBindingSource.DataSource = this.hotelDataSet;
            // 
            // hotelDataSet
            // 
            this.hotelDataSet.DataSetName = "HotelDataSet";
            this.hotelDataSet.SchemaSerializationMode = System.Data.SchemaSerializationMode.IncludeSchema;
            // 
            // menuReportPersonCnt
            // 
            this.menuReportPersonCnt.Name = "menuReportPersonCnt";
            this.menuReportPersonCnt.Size = new System.Drawing.Size(245, 22);
            this.menuReportPersonCnt.Text = "Количество проживающих";
            this.menuReportPersonCnt.Click += new System.EventHandler(this.menuReportPersonCnt_Click);
            // 
            // menuReportReservation
            // 
            this.menuReportReservation.Name = "menuReportReservation";
            this.menuReportReservation.Size = new System.Drawing.Size(245, 22);
            this.menuReportReservation.Text = "Забронированные номера";
            this.menuReportReservation.Click += new System.EventHandler(this.menuReportReservation_Click);
            // 
            // menuReportRoomType
            // 
            this.menuReportRoomType.Name = "menuReportRoomType";
            this.menuReportRoomType.Size = new System.Drawing.Size(245, 22);
            this.menuReportRoomType.Text = "Количество номеров по типам";
            this.menuReportRoomType.Click += new System.EventHandler(this.menuReportRoomType_Click);
            // 
            // MainForm
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(584, 361);
            this.Controls.Add(this.dgvMain);
            this.Controls.Add(this.menuStrip1);
            this.MainMenuStrip = this.menuStrip1;
            this.Name = "MainForm";
            this.Text = "ИС бронирования и сдачи номеров";
            this.Load += new System.EventHandler(this.MainForm_Load);
            this.menuStrip1.ResumeLayout(false);
            this.menuStrip1.PerformLayout();
            ((System.ComponentModel.ISupportInitialize)(this.dgvMain)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.movesViewBindingSource)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.hotelDataSet)).EndInit();
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private System.Windows.Forms.MenuStrip menuStrip1;
        private System.Windows.Forms.ToolStripMenuItem menuRef;
        private System.Windows.Forms.ToolStripMenuItem menuTypes;
        private System.Windows.Forms.ToolStripMenuItem menuRooms;
        private System.Windows.Forms.ToolStripMenuItem menuUsers;
        private System.Windows.Forms.ToolStripMenuItem menuMoves;
        private System.Windows.Forms.ToolStripMenuItem menuMoveNew;
        private System.Windows.Forms.ToolStripMenuItem menuMoveEdit;
        private System.Windows.Forms.ToolStripMenuItem menuMoveDel;
        private System.Windows.Forms.ToolStripMenuItem menuReports;
        private System.Windows.Forms.DataGridView dgvMain;
        private HotelDataSet hotelDataSet;
        private System.Windows.Forms.BindingSource movesViewBindingSource;
        private System.Windows.Forms.DataGridViewTextBoxColumn idDataGridViewTextBoxColumn;
        private System.Windows.Forms.DataGridViewTextBoxColumn datesDataGridViewTextBoxColumn;
        private System.Windows.Forms.DataGridViewTextBoxColumn actionNameDataGridViewTextBoxColumn;
        private System.Windows.Forms.DataGridViewTextBoxColumn roomIdDataGridViewTextBoxColumn;
        private System.Windows.Forms.DataGridViewTextBoxColumn datesInDataGridViewTextBoxColumn;
        private System.Windows.Forms.DataGridViewTextBoxColumn daysCntDataGridViewTextBoxColumn;
        private System.Windows.Forms.DataGridViewTextBoxColumn datesOutDataGridViewTextBoxColumn;
        private System.Windows.Forms.DataGridViewTextBoxColumn personsCntDataGridViewTextBoxColumn;
        private System.Windows.Forms.DataGridViewTextBoxColumn userNameDataGridViewTextBoxColumn;
        private System.Windows.Forms.ToolStripMenuItem menuReportPersonCnt;
        private System.Windows.Forms.ToolStripMenuItem menuReportReservation;
        private System.Windows.Forms.ToolStripMenuItem menuReportRoomType;
    }
}