<%@ Page Title="" Language="C#" MasterPageFile="~/Template.Master" AutoEventWireup="true" CodeBehind="Topics.aspx.cs" Inherits="ForumSimple.Topics" %>
<asp:Content ID="Content1" ContentPlaceHolderID="head" runat="server">
</asp:Content>
<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" runat="server">
    <h3><asp:Literal ID="literalRubricName" runat="server" /></h3>
    <asp:Label ID="hiddenRubricID" runat="server" Text="0" Visible="false" />
    <asp:Label ID="hiddenUserID" runat="server" Text="0" Visible="false" />
    <asp:ListView ID="ListViewTopics" runat="server" DataKeyNames="id" DataSourceID="dsTopicsNew" InsertItemPosition="LastItem">
        <EditItemTemplate>
            <tr>
                <td colspan="4">
                    <h4>Изменить тему</h4>
                    <div class="form-group">
                        <label for="nameTextBox">Наименование</label>
                        <asp:TextBox ID="nameTextBox" runat="server" Text='<%# Bind("name") %>' CssClass="form-control" ValidationGroup="UpdateValidationGroup" />
                        <span class="help-block">
                            <asp:RequiredFieldValidator runat="server" ID="RequiredFieldValidator1" ControlToValidate="nameTextBox" ValidationGroup="UpdateValidationGroup" Display="Dynamic">Наименование не может быть пустым</asp:RequiredFieldValidator>
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="messageTextBox">Стартовое сообщение</label>
                        <asp:TextBox ID="messageTextBox" runat="server" Text='<%# Bind("message") %>' CssClass="form-control" Rows="5" TextMode="MultiLine" ValidationGroup="UpdateValidationGroup" />
                        <span class="help-block">
                            <asp:RequiredFieldValidator runat="server" ID="RequiredFieldValidator2" ControlToValidate="messageTextBox" ValidationGroup="UpdateValidationGroup" Display="Dynamic">Сообщение не может быть пустым</asp:RequiredFieldValidator>
                        </span>
                    </div>
                </td>
                <td>
                    <asp:LinkButton ID="UpdateButton" runat="server" CommandName="Update" CssClass="btn btn-default btn-sm" ValidationGroup="UpdateValidationGroup"><span class="glyphicon glyphicon-ok"></span></asp:LinkButton>
                    <asp:LinkButton ID="CancelButton" runat="server" CommandName="Cancel" CssClass="btn btn-default btn-sm"><span class="glyphicon glyphicon-repeat"></span></asp:LinkButton>
                </td>
            </tr>
        </EditItemTemplate>
        <EmptyDataTemplate>
            Нет данных...
        </EmptyDataTemplate>
        <InsertItemTemplate>
            <tr runat="server" visible='<%# AllowInsert() %>'>
                <td colspan="4">
                    <h4>Добавить тему</h4>
                    <div class="form-group">
                        <label for="nameTextBox">Наименование</label>
                        <asp:TextBox ID="nameTextBox" runat="server" Text='<%# Bind("name") %>' CssClass="form-control" ValidationGroup="InsertValidationGroup" />
                        <span class="help-block">
                            <asp:RequiredFieldValidator runat="server" ID="RequiredFieldValidator1" ControlToValidate="nameTextBox" ValidationGroup="InsertValidationGroup" Display="Dynamic">Наименование не может быть пустым</asp:RequiredFieldValidator>
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="messageTextBox">Стартовое сообщение</label>
                        <asp:TextBox ID="messageTextBox" runat="server" Text='<%# Bind("message") %>' CssClass="form-control" Rows="5" TextMode="MultiLine" ValidationGroup="InsertValidationGroup" />
                        <span class="help-block">
                            <asp:RequiredFieldValidator runat="server" ID="RequiredFieldValidator2" ControlToValidate="messageTextBox" ValidationGroup="InsertValidationGroup" Display="Dynamic">Сообщение не может быть пустым</asp:RequiredFieldValidator>
                        </span>
                    </div>
                </td>
                <td>
                    <asp:LinkButton ID="InsertButton" runat="server" CommandName="Insert" CssClass="btn btn-default btn-sm" ValidationGroup="InsertValidationGroup"><span class="glyphicon glyphicon-ok"></span></asp:LinkButton>
                    <asp:LinkButton ID="CancelButton" runat="server" CommandName="Cancel" CssClass="btn btn-default btn-sm"><span class="glyphicon glyphicon-repeat"></span></asp:LinkButton>
                </td>
            </tr>
        </InsertItemTemplate>
        <ItemTemplate>
            <tr>
                <td>
                    <p>
                        <asp:HyperLink ID="HyperLink1" runat="server" Text='<%# Eval("name") %>' NavigateUrl='<%# "~/Messages.aspx?TopicID=" + Eval("id") %>' />
                    </p>
                    <small>
                        <asp:Label ID="Label1" runat="server" Text='<%# Eval("message") %>' />
                    </small>
                </td>
                <td>
                    <asp:HyperLink ID="linkUser" runat="server" Text='<%# Eval("topicStarter") %>' NavigateUrl='<%# "~/Profile.aspx?UserID=" + Eval("users") %>' />
                </td>
                <td>
                    <asp:Label ID="Label3" runat="server" Text='<%# Eval("cntMsg") %>' />
                </td>
                <td>
                    <asp:Label ID="Label5" runat="server" Text='<%# Eval("lastWriter") %>' />
                    <br />
                    <small><asp:Label ID="Label4" runat="server" Text='<%# Eval("dates") %>' /></small>
                </td>
                <td>
                    <asp:LoginView ID="insertLoginView" runat="server">
                        <RoleGroups>
                            <asp:RoleGroup Roles="Admin, Moder">
                                <ContentTemplate>
                                    <asp:LinkButton ID="EditButton" runat="server" CommandName="Edit" CssClass="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil"></span></asp:LinkButton>
                                    <asp:LinkButton ID="DeleteButton" runat="server" CommandName="Delete" CssClass="btn btn-default btn-sm"><span class="glyphicon glyphicon-remove"></span></asp:LinkButton>
                                </ContentTemplate>
                            </asp:RoleGroup>
                        </RoleGroups>
                    </asp:LoginView>
                </td>
            </tr>
        </ItemTemplate>
        <LayoutTemplate>
            <table ID="itemPlaceholderContainer" runat="server" class="table table-striped">
                <thead>
                    <tr runat="server">
                        <th runat="server">Тема</th>
                        <th runat="server">Автор</th>
                        <th runat="server">Ответов</th>
                        <th runat="server">Активность</th>
                        <th runat="server">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ID="itemPlaceholder" runat="server">
                    </tr>
                </tbody>
            </table>
            <asp:DataPager ID="DataPager1" runat="server">
                <Fields>
                    <asp:NextPreviousPagerField ButtonType="Button" ShowFirstPageButton="True" ShowNextPageButton="False" ShowPreviousPageButton="False" ButtonCssClass="btn btn-default btn-sm" />
                    <asp:NumericPagerField />
                    <asp:NextPreviousPagerField ButtonType="Button" ShowLastPageButton="True" ShowNextPageButton="False" ShowPreviousPageButton="False" ButtonCssClass="btn btn-default btn-sm" />
                </Fields>
            </asp:DataPager>
        </LayoutTemplate>
    </asp:ListView>
    <asp:ObjectDataSource ID="dsTopicsNew" runat="server" 
        DeleteMethod="DeleteQuery" InsertMethod="InsertQuery" 
        OldValuesParameterFormatString="original_{0}" SelectMethod="GetDataByRubrics" 
        TypeName="ForumSimple.ForumDataSetTableAdapters.topicViewTableAdapter" 
        UpdateMethod="UpdateQuery">
        <DeleteParameters>
            <asp:Parameter Name="Original_id" Type="Int32" />
        </DeleteParameters>
        <InsertParameters>
            <asp:Parameter Name="name" Type="String" />
            <asp:ControlParameter ControlID="hiddenRubricID" DefaultValue="0" 
                Name="rubrics" PropertyName="Text" Type="Int32" />
            <asp:ControlParameter ControlID="hiddenUserID" DefaultValue="0" 
                Name="users" PropertyName="Text" Type="Int32" />
            <asp:Parameter Name="message" Type="String" />
        </InsertParameters>
        <SelectParameters>
            <asp:ControlParameter ControlID="hiddenRubricID" DefaultValue="0" 
                Name="rubrics" PropertyName="Text" Type="Int32" />
        </SelectParameters>
        <UpdateParameters>
            <asp:Parameter Name="name" Type="String" />
            <asp:Parameter Name="message" Type="String" />
            <asp:Parameter Name="Original_id" Type="Int32" />
        </UpdateParameters>
    </asp:ObjectDataSource>
</asp:Content>
